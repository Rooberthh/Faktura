<?php

namespace Rooberthh\Faktura\Actions;

use Rooberthh\Faktura\Models\Invoice;

class InitializeInvoiceOnProviderAction
{
    public function execute(Invoice $invoice)
    {
        $gateway = $invoice->gateway();
        $gateway->createInvoice($invoice);
    }
}
