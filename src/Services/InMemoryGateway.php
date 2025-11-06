<?php

namespace Rooberthh\Faktura\Services;

use Exception;
use Illuminate\Support\Str;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\Enums\Provider;

class InMemoryGateway implements GatewayContract
{
    public function createInvoice(Invoice $invoice): InvoiceDTO
    {
        $invoice->external_id = Str::uuid()->toString();
        $invoice->provider = Provider::IN_MEMORY;
        $invoice->save();

        return InvoiceDTO::fromInvoice($invoice);
    }

    public function get(string $externalId): InvoiceDTO
    {
        $invoice = Invoice::query()
            ->where('provider', Provider::IN_MEMORY)
            ->where('external_id', $externalId)
            ->first();

        return InvoiceDTO::fromInvoice($invoice);
    }
}
