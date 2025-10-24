<?php

use Illuminate\Support\Str;
use Rooberthh\Faktura\Actions\InitializeInvoiceOnProviderAction;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Exceptions\InvoiceAlreadyInitializedException;
use Rooberthh\Faktura\Services\InMemoryGateway;
use Rooberthh\Faktura\Support\Enums\Provider;
use Rooberthh\Faktura\Tests\Stubs\FakeInMemoryGateway;

it('can initialize a new invoice', function () {
    $gateway = new FakeInMemoryGateway();
    $this->app->instance(InMemoryGateway::class, $gateway);

    $invoice = InvoiceFactory::new()->create(['provider' => Provider::IN_MEMORY, 'external_id' => null]);

    expect($invoice->external_id)->toBeNull();

    (new InitializeInvoiceOnProviderAction())->execute($invoice);

    expect($invoice->provider)->toBe(Provider::IN_MEMORY)->and($invoice->external_id)->not->toBeNull();

    $gateway->assertExists($invoice->external_id);
});

it('throws an exception if the invoice is already synced to an external provider and tries to be initialized again', function () {
    $invoice = InvoiceFactory::new()->create(
        [
            'provider' => Provider::IN_MEMORY,
            'external_id' => Str::uuid()->toString(),
        ],
    );

    expect(fn() => (new InitializeInvoiceOnProviderAction())->execute($invoice))
        ->toThrow(InvoiceAlreadyInitializedException::class)
        ->and($invoice->provider)->toBe(Provider::IN_MEMORY)
        ->and($invoice->external_id)->toBe($invoice->external_id);
});
