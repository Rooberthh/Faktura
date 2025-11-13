<?php

namespace Rooberthh\Faktura\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Rooberthh\Faktura\Models\Invoice;

class SyncInvoiceJob implements ShouldQueueAfterCommit
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $invoiceId)
    {
        //
    }

    public function handle(): void
    {
        $invoice = Invoice::query()->findOrFail($this->invoiceId);

        $invoiceDTO = $invoice->gateway()->get($invoice->external_id);

        $invoice->syncFromDto($invoiceDTO);
    }

    public function middleware(): array
    {
        return [
            new WithoutOverlapping((string) $this->invoiceId),
        ];
    }
}
