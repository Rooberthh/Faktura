<?php

use Illuminate\Support\Str;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Jobs\SyncInvoiceJob;
use Rooberthh\Faktura\Support\DataObjects\Invoice;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Price;

it('can sync an invoice of an invoiceDTO', function () {
    $externalId = Str::uuid()->toString();

    $invoice = InvoiceFactory::new()
        ->stripe()
        ->create(
            [
                'external_id' => $externalId,
            ],
        );

    $lines = [
        new InvoiceLine(
            sku: 'test-sku',
            description: 'test description',
            quantity: 1,
            unitPriceExVat: Price::fromMinor(75),
            unitVatAmount: Price::fromMinor(25),
            unitPriceIncVat: Price::fromMinor(100),
            vatRate: 25,
            subTotal: Price::fromMinor(75),
            vatTotal: Price::fromMinor(25),
            total: Price::fromMinor(100),
            metadata: [],
        ),
        new InvoiceLine(
            sku: 'test-sku-2',
            description: 'test description',
            quantity: 2,
            unitPriceExVat: Price::fromMinor(75),
            unitVatAmount: Price::fromMinor(25),
            unitPriceIncVat: Price::fromMinor(100),
            vatRate: 25,
            subTotal: Price::fromMinor(150),
            vatTotal: Price::fromMinor(50),
            total: Price::fromMinor(200),
            metadata: [],
        ),
    ];

    $invoiceDTO = new Invoice(
        externalId: $externalId,
        provider: $invoice->provider,
        status: Status::Paid,
        total: Price::fromMinor(300),
        lines: collect($lines),
    );

    expect($invoice->lines)->toBeEmpty()->and($invoice->status)->toBe(Status::Draft);

    $job = new SyncInvoiceJob($invoice->id, $invoiceDTO);
    $job->handle();

    $invoice->refresh();

    expect($invoice->status)->toBe(Status::Paid)
        ->and($invoice->lines)->toHaveCount(2)
        ->and($invoice->total->amount())->toBe(300);
});
