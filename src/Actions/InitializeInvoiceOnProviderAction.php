<?php

namespace Rooberthh\Faktura\Actions;

use DomainException;
use Rooberthh\Faktura\Models\Invoice;

class InitializeInvoiceOnProviderAction
{
    public function execute(Invoice $invoice)
    {
        if ($invoice->external_id) {
            throw new DomainException('Invoice already have an external id and cannot be created on another provider.');
        }

        $gateway = $invoice->gateway();
        $gateway->createInvoice($invoice);
    }
}
