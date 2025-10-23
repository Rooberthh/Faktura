<?php

namespace Rooberthh\Faktura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rooberthh\Faktura\Casts\PriceCast;
use Rooberthh\Faktura\Database\Factories\InvoiceLineFactory;
use Rooberthh\Faktura\Support\Objects\Price;

/**
 * @property int        $id
 * @property string     $sku
 * @property string     $description
 * @property int        $quantity
 * @property Price $unit_price_ex_vat
 * @property Price $unit_vat_amount
 * @property Price $unit_price_inc_vat
 * @property int $vat_rate
 * @property Price $sub_total
 * @property Price $vat_total
 * @property Price $total
 * @property Invoice $invoice
 * @property Model $invoiceable
 */
class InvoiceLine extends Model
{
    /** @use HasFactory<InvoiceLineFactory>  */
    use HasFactory;

    protected $fillable = [
        'sku',
        'description',
        'quantity',
        'unit_price_ex_vat',
        'unit_vat_amount',
        'unit_price_inc_vat',
        'vat_rate',
        'sub_total',
        'vat_total',
        'total',
        'metadata',
    ];

    public function getTable(): string
    {
        return config('faktura.table_prefix') . 'invoice_lines';
    }

    /**
     * Get the invoice.
     *
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the invoiceable item.
     *
     * @return MorphTo<Model, $this>
     */
    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'unit_price_ex_vat' => PriceCast::class,
            'unit_vat_amount' => PriceCast::class,
            'unit_price_inc_vat' => PriceCast::class,
            'sub_total' => PriceCast::class,
            'vat_total' => PriceCast::class,
            'total' => PriceCast::class,
        ];
    }
}
