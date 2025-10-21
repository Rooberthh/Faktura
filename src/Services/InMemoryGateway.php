<?php

namespace Rooberthh\Faktura\Services;

use Exception;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;

class InMemoryGateway implements GatewayContract
{
    public function createInvoice(Invoice $invoice): Invoice
    {
        throw new Exception('Not implemented');
    }

    public function handleCallback()
    {
        throw new Exception('Not implemented');
    }
}
