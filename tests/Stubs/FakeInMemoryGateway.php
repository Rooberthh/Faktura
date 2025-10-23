<?php

namespace Rooberthh\Faktura\Tests\Stubs;

use Illuminate\Support\Str;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\Enums\Provider;

class FakeInMemoryGateway implements GatewayContract
{
    /** @var InvoiceDTO[]  */
    public array $invoices = [];

    public function get(string $externalId): InvoiceDTO
    {
        return collect($this->invoices)->firstWhere('externalId', $externalId);
    }

    public function createInvoice(Invoice $invoice): InvoiceDTO
    {
        $invoice->external_id = Str::uuid()->toString();
        $invoice->provider = Provider::IN_MEMORY;
        $invoice->save();

        $invoiceDTO = $invoice->toDto();

        $this->invoices[] = $invoiceDTO;

        return $invoiceDTO;
    }

    public function findInvoiceById(string $invoiceId): ?InvoiceDTO
    {
        return collect($this->invoices)->first(fn(InvoiceDTO $invoice) => $invoice->externalId === $invoiceId);
    }


    public function exists(string $invoiceId): bool
    {
        return (bool) $this->findInvoiceById($invoiceId);
    }

    public function assertExists(string $invoiceId): void
    {
        if (! $this->exists($invoiceId)) {
            throw new \AssertionError("Expected invoice [{$invoiceId}] to exist in InMemoryGateway.");
        }
    }
}
