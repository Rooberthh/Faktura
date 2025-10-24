<?php

namespace Rooberthh\Faktura\Actions;

use Rooberthh\Faktura\Exceptions\InvoiceAlreadyInitializedException;
use Rooberthh\Faktura\Models\Invoice;

class InitializeInvoiceOnProviderAction
{
    /**
     * @throws InvoiceAlreadyInitializedException
     */
    public function execute(Invoice $invoice): Invoice
    {
        if ($invoice->external_id) {
            throw new InvoiceAlreadyInitializedException('Invoice already have an external id and cannot be re-created on a provider.');
        }

        $gateway = $invoice->gateway();
        $gateway->createInvoice($invoice);

        return $invoice;
    }
}
