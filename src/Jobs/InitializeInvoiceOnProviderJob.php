<?php

namespace Rooberthh\Faktura\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Actions\InitializeInvoiceOnProviderAction;

class InitializeInvoiceOnProviderJob implements ShouldQueueAfterCommit
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected int $invoiceId)
    {
        //
    }

    public function handle(InitializeInvoiceOnProviderAction $action): void
    {
        $invoice = Invoice::query()->findOrFail($this->invoiceId);

        $action->execute($invoice);
    }
}
