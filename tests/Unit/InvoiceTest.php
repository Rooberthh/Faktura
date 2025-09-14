<?php

use Money\Money;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Database\Factories\InvoiceLineFactory;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Models\InvoiceLine;
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
