<?php

namespace Rooberthh\Faktura\Contracts;

use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;

interface GatewayContract
{
    public function get(string $externalId): InvoiceDTO;

    public function createInvoice(Invoice $invoice): InvoiceDTO;

    public function handleCallback();
}
