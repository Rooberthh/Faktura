<?php

namespace Rooberthh\Faktura\Contracts;

use Rooberthh\Faktura\Models\Invoice;

interface GatewayContract
{
    public function createInvoice(Invoice $invoice);

    public function handleCallback();
}
