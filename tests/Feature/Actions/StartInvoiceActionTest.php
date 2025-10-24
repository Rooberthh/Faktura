<?php

use Rooberthh\Faktura\Actions\StartInvoiceAction;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine;
use Rooberthh\Faktura\Support\Objects\Price;
use Rooberthh\Faktura\Support\Objects\Seller;
use Rooberthh\Faktura\Tests\Stubs\FakeBillableUser;

it('can start an invoice', function () {
    $billable = new FakeBillableUser();
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

    $seller = new Seller(
        name: 'Default',
        address: 'test',
        city: 'test',
        postalCode: '54135',
        country: 'sweden',
        orgNumber: '199803199570',
        vatNumber: '199803199570',
        iban: 'my-iban-number',
    );

    $invoice = (new StartInvoiceAction())->execute(
        billable: $billable,
        seller: $seller,
        lines: $lines,
    );

    expect($invoice->buyer->toArray())->toBe($buyer->toArray())
        ->and($invoice->lines)->toHaveCount(2)
        ->and($invoice->total->amount())->toBe(300);
});
