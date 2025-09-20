<?php

namespace Rooberthh\Faktura\Services;

use Exception;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;

class StripeGateway implements GatewayContract
{
    public function createInvoice(Invoice $invoice)
    {
        throw new Exception('Not implemented');
    }

    public function handleCallback()
    {
        throw new Exception('Not implemented');
    }
}
