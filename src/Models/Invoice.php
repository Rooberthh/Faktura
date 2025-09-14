<?php

namespace Rooberthh\Faktura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Billing\Domain\Support\Objects\Seller;
use Rooberthh\Faktura\Casts\BuyerCast;
use Rooberthh\Faktura\Casts\PriceCast;
use Rooberthh\Faktura\Casts\SellerCast;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Buyer;
use Rooberthh\Faktura\Support\Objects\Price;

/**
 * @property int $id
 * @property string $invoice_number
 * @property Status $status
 * @property Buyer $buyer
 * @property Seller $seller
 * @property Price $total
 * @property string $provider
 * @property string $external_id
 */
class Invoice extends Model
{
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
        'provider',
        'external_id',
        'metadata',
    ];

    public function getTable(): string
    {
        return config('faktura.table_prefix') . 'invoices';
    }

    /**
     * @return HasMany
     */
    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'seller' => SellerCast::class,
            'buyer' => BuyerCast::class,
            'total' => PriceCast::class,
        ];
    }
}
