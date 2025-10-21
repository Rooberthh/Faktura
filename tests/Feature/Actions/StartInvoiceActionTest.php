<?php

use Rooberthh\Faktura\Actions\StartInvoiceAction;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine;
use Rooberthh\Faktura\Support\Objects\Price;
use Rooberthh\Faktura\Tests\Stubs\BillableUser;

it('can start an invoice', function () {
    $billable = new BillableUser();
    $buyer = $billable->toBuyer();

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

    $invoice = (new StartInvoiceAction())->execute(
        billable: $billable,
        lines: $lines,
    );

    expect($invoice->buyer->toArray())->toBe($buyer->toArray())
        ->and($invoice->lines)->toHaveCount(2)
        ->and($invoice->total->amount())->toBe(300);
});
