<?php

namespace Rooberthh\Faktura\Tests\Stubs;

use Illuminate\Support\Str;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\Enums\Provider;

class FakeInMemoryGateway implements GatewayContract
{
    /** @var Invoice[]  */
    public array $invoices = [];

    public function createInvoice(Invoice $invoice)
    {
        $invoice->external_id = Str::uuid()->toString();
        $invoice->provider = Provider::IN_MEMORY;
        $invoice->save();

        $this->invoices[] = $invoice;
    }

    public function handleCallback()
    {
        // TODO: Implement handleCallback() method.
    }

    public function findInvoiceById(int $invoiceId): ?Invoice
    {
        return collect($this->invoices)->first(fn($invoice) => $invoice->id === $invoiceId);
    }


    public function exists(int $invoiceId): bool
    {
        return (bool) $this->findInvoiceById($invoiceId);
    }

    public function assertExists(int $invoiceId): void
    {
        if (! $this->exists($invoiceId)) {
            throw new \AssertionError("Expected invoice [{$invoiceId}] to exist in InMemoryGateway.");
        }
    }
}
