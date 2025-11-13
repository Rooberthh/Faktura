<?php

namespace Rooberthh\Faktura\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Rooberthh\Faktura\Casts\EventMetadataCast;
use Rooberthh\Faktura\Support\Enums\EventType;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Deleted;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Finalized;
use Rooberthh\Faktura\Support\Objects\EventMetadata\MarkedUncollectible;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Paid;
use Rooberthh\Faktura\Support\Objects\EventMetadata\PaymentFailed;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Voided;

/**
 * @property int                                                                        $id
 * @property int                                                                        $invoice_id
 * @property EventType                                                                  $type
 * @property Paid|Voided|MarkedUncollectible|Finalized|Deleted|PaymentFailed|array|null $metadata
 * @property Carbon                                                                     $occurred_at
 * @property Carbon                                                                     $created_at
 * @property Carbon                                                                     $updated_at
 * @property Invoice                                                                    $invoice
 */
class InvoiceEvent extends Model
{
    protected $fillable = [
        'invoice_id',
        'type',
        'metadata',
        'occurred_at',
    ];

    public function getTable(): string
    {
        return config('faktura.table_prefix') . 'invoice_events';
    }

    /** @return BelongsTo<Invoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected function casts(): array
    {
        return [
            'type' => EventType::class,
            'metadata' => EventMetadataCast::class,
            'occurred_at' => 'datetime',
        ];
    }
}
