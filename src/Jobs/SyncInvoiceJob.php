<?php

namespace Rooberthh\Faktura\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;

class SyncInvoiceJob implements ShouldQueueAfterCommit
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $invoiceId, public InvoiceDTO $invoiceDTO)
    {
        //
    }

    public function handle(): void
    {
        $invoice = Invoice::query()->findOrFail($this->invoiceId);
        $invoice->syncFromDto($this->invoiceDTO);
    }
}
