<?php

namespace Rooberthh\Faktura\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Rooberthh\Faktura\Casts\BuyerCast;
use Rooberthh\Faktura\Casts\PriceCast;
use Rooberthh\Faktura\Casts\SellerCast;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Services\InMemoryGateway;
use Rooberthh\Faktura\Services\Stripe\StripeGateway;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine as InvoiceLineDTO;
use Rooberthh\Faktura\Support\Enums\Provider;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Buyer;
use Rooberthh\Faktura\Support\Objects\Price;
use Rooberthh\Faktura\Support\Objects\Seller;

/**
 * @property int $id
 * @property string $number
 * @property Status $status
 * @property Buyer $buyer
 * @property Seller $seller
 * @property Price $total
 * @property Provider $provider
 * @property string $external_id
 * @property Collection<int, InvoiceLine> $lines
 * @property Model $billable
 * @method Builder<Invoice> provider()
 */
class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'status',
        'billable_id',
        'billable_type',
        'billing_name',
        'billing_address',
        'billing_postal_code',
        'billing_city',
        'billing_country',
        'billing_org_number',
        'billing_vat_number',
        'seller_name',
        'seller_address',
        'seller_postal_code',
        'seller_city',
        'seller_country',
        'seller_org_number',
        'seller_vat_number',
        'seller_iban',
        'seller_payment_reference',
        'total',
        'provider',
        'external_id',
        'metadata',
    ];

    public function getTable(): string
    {
        return config('faktura.table_prefix') . 'invoices';
    }

    /** @return HasMany<InvoiceLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /** @return MorphTo<Model, $this> */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function gateway(): GatewayContract
    {
        return match ($this->provider) {
            Provider::STRIPE => app(StripeGateway::class),
            Provider::IN_MEMORY => app(InMemoryGateway::class),
        };
    }

    public function syncFromDto(InvoiceDTO $invoiceDto): void
    {
        DB::transaction(function () use ($invoiceDto) {
            $this->status = $invoiceDto->status;
            $this->external_id = $invoiceDto->externalId;
            $this->provider = $invoiceDto->provider;
            $this->save();

            // Delete and re-add lines since this is just a read-replica
            $this->lines()->delete();
            $this->lines()->createMany(
                $invoiceDto->lines->map(function (InvoiceLineDTO $line) {
                    return [
                        'sku' => $line->sku,
                        'description' => $line->description,
                        'quantity' => $line->quantity,
                        'unit_price_ex_vat' => $line->unitPriceExVat,
                        'unit_vat_amount' => $line->unitVatAmount,
                        'unit_price_inc_vat' => $line->unitPriceIncVat,
                        'vat_rate' => $line->vatRate,
                        'sub_total' => $line->subTotal,
                        'vat_total' => $line->vatTotal,
                        'total' => $line->total,
                        'metadata' => json_encode($line->metadata),
                    ];
                })->toArray(),
            );

            $this->recalculateTotals();
        }, 3);
    }

    /**
     * @param  Builder<Invoice> $query
     * @param Provider $provider
     * @return Builder<Invoice>
     */
    public function scopeProvider(Builder $query, Provider $provider): Builder
    {
        return $query->where('provider', $provider);
    }

    public function addLine(InvoiceLineDTO $line): void
    {
        $this->lines->push(
            new InvoiceLine(
                [
                    'sku' => $line->sku,
                    'description' => $line->description,
                    'quantity' => $line->quantity,
                    'unit_price_ex_vat' => $line->unitPriceExVat,
                    'unit_vat_amount' => $line->unitVatAmount,
                    'unit_price_inc_vat' => $line->unitPriceIncVat,
                    'vat_rate' => $line->vatRate,
                    'sub_total' => $line->subTotal,
                    'vat_total' => $line->vatTotal,
                    'total' => $line->total,
                    'metadata' => json_encode($line->metadata),
                ],
            ),
        );
    }

    public function recalculateTotals(): void
    {
        $this->total = Price::fromMinor($this->lines()->sum('total'));
        $this->save();
    }

    public function toDto(): InvoiceDTO
    {
        return new InvoiceDTO(
            externalId: $this->external_id,
            provider: $this->provider,
            status: $this->status,
            total: $this->total,
            lines: $this->lines->map(function (InvoiceLine $line) {
                return new InvoiceLineDTO(
                    sku: $line->sku,
                    description: $line->description,
                    quantity: $line->quantity,
                    unitPriceExVat: $line->unit_price_ex_vat,
                    unitVatAmount: $line->unit_vat_amount,
                    unitPriceIncVat: $line->unit_price_inc_vat,
                    vatRate: $line->vat_rate,
                    subTotal: $line->sub_total,
                    vatTotal: $line->vat_total,
                    total: $line->total,
                    metadata: [],
                );
            }),
        );
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'provider' => Provider::class,
            'seller' => SellerCast::class,
            'buyer' => BuyerCast::class,
            'total' => PriceCast::class,
        ];
    }
}
