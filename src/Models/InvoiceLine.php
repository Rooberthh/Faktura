<?php

namespace Rooberthh\Faktura\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Rooberthh\Faktura\Casts\PriceCast;

/**
 * @property int            $id
 * @property string         $description
 * @property int            $vat_rate
 */
class InvoiceLine extends Model
{
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
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the invoiceable item.
     *
     * @return MorphTo
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
