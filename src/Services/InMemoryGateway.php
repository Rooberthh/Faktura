<?php

namespace Rooberthh\Faktura\Services;

use Exception;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;

class InMemoryGateway implements GatewayContract
{
    public function createInvoice(Invoice $invoice): InvoiceDTO
    {
        throw new Exception('Not implemented');
    }

    public function handleCallback()
    {
        throw new Exception('Not implemented');
    }

    public function get(string $externalId): InvoiceDTO
    {
        throw new Exception('Not implemented');
    }
}
