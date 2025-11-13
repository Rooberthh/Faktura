<?php

namespace Rooberthh\Faktura\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Rooberthh\Faktura\Contracts\Billable;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine;
use Rooberthh\Faktura\Support\Enums\EventType;

class RecordEventAction
{
    /**
     * @param  Billable&Model $billable
     * @param  InvoiceLine[] $lines
     * @return Invoice
     */
    public function execute(Invoice $invoice, EventType $eventType): void
    {
        DB::transaction(function () use ($invoice, $eventType) {
            $invoice->events()->create(
                [
                    'type' => $eventType,
                    'occurred_at' => now(),
                ],
            );
        }, 3);
    }
}
