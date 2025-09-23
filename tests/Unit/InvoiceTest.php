<?php

use Illuminate\Support\Str;
use Money\Money;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Database\Factories\InvoiceLineFactory;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Models\InvoiceLine;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine as InvoiceLineDTO;
use Rooberthh\Faktura\Support\Enums\Provider;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Buyer;
use Rooberthh\Faktura\Support\Objects\Price;
use Rooberthh\Faktura\Support\Objects\Seller;

it('can be created and has a status', function () {
    $invoice = InvoiceFactory::new()->create();

    expect($invoice)->toBeInstanceOf(Invoice::class)->and($invoice->status)->toBe(Status::Draft);
});

it('has invoice-lines', function () {
    $invoice = InvoiceFactory::new()
        ->has(InvoiceLineFactory::new(), 'lines')
        ->create();

    $invoiceLine = $invoice->lines()->first();

    expect($invoiceLine)->toBeInstanceOf(InvoiceLine::class)
        ->and($invoiceLine->invoice_id)
        ->toBe($invoice->id);
});

it('has a total', function () {
    $invoice = InvoiceFactory::new()
        ->has(InvoiceLineFactory::new(), 'lines')
        ->create();

    expect($invoice->total)->toBeInstanceOf(Price::class)
        ->and($invoice->getRawOriginal('total'))
        ->toBe($invoice->total->amount())
        ->and($invoice->total->money())
        ->toBeInstanceOf(Money::class);
});

it('can sync itself from an InvoiceDTO', function () {
    $uuid = Str::uuid()->toString();

    $invoice = InvoiceFactory::new(['external_id' => $uuid])->stripe()->create();

    $invoiceDTO = new InvoiceDTO(
        externalId: $uuid,
        provider: Provider::STRIPE,
        status: Status::Paid,
        total: Price::fromMinor(20000),
        lines: collect(
            [
                new InvoiceLineDTO(
                    sku: 'my-test-sku',
                    description: 'My new item description',
                    quantity: 2,
                    unitPriceExVat: Price::fromMinor(7500),
                    unitVatAmount: Price::fromMinor(2500),
                    unitPriceIncVat: Price::fromMinor(10000),
                    vatRate: 25,
                    subTotal: Price::fromMinor(15000),
                    vatTotal: Price::fromMinor(5000),
                    total: Price::fromMinor(20000),
                    metadata: [],
                ),
            ],
        ),
    );

    $invoice->syncFromDto($invoiceDTO);

    $invoice->refresh();

    /** @var InvoiceLine $invoiceLine */
    $invoiceLine = $invoice->lines()->first();

    expect($invoice->external_id)->toBe($uuid)
    ->and($invoice->total->amount())->toBe(20000)
    ->and($invoice->status)->toBe(Status::Paid)
    ->and($invoiceLine->description)->toBe('My new item description')
    ->and($invoiceLine->quantity)->toBe(2)
    ->and($invoiceLine->unit_price_ex_vat->amount())->toBe(7500)
    ->and($invoiceLine->unit_vat_amount->amount())->toBe(2500)
    ->and($invoiceLine->unit_price_inc_vat->amount())->toBe(10000)
    ->and($invoiceLine->vat_rate)->toBe(25)
    ->and($invoiceLine->sub_total->amount())->toBe(15000)
    ->and($invoiceLine->vat_total->amount())->toBe(5000)
    ->and($invoiceLine->total->amount())->toBe(20000);
});

it('can have a buyer', function () {
    $invoice = InvoiceFactory::new()
        ->has(InvoiceLineFactory::new(), 'lines')
        ->create();

    expect($invoice->buyer)->toBeInstanceOf(Buyer::class)->and($invoice->buyer->name)->toBe($invoice->billing_name);
});

it('can have a seller', function () {
    $invoice = InvoiceFactory::new()
        ->has(InvoiceLineFactory::new(), 'lines')
        ->create();

    expect($invoice->seller)->toBeInstanceOf(Seller::class)->and($invoice->seller->name)->toBe($invoice->seller_name);
});

it('can have an external provider', function () {
    $invoice = InvoiceFactory::new()
        ->has(InvoiceLineFactory::new(), 'lines')
        ->create();

    $invoice->provider = Provider::STRIPE;
    $invoice->save();

    expect($invoice->provider)->toBeInstanceOf(Provider::class)->and($invoice->provider)->toBe(Provider::STRIPE);
});
