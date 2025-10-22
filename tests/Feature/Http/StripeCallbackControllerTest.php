<?php

use Illuminate\Support\Facades\Bus;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Jobs\SyncInvoiceJob;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Models\Invoice;

it('dispatches a job that handles the syncing of an invoice for the invoice-paid', function () {
    Config::set('faktura.stripe.webhook_secret', 'whsec_test_secret');
    Bus::fake();

    // Load the fixture for checkout success.
    $payload = $this->loadFixture('stripe-invoice-paid');

    // Create an invoice with empty lines
    $invoice = InvoiceFactory::new()
        ->stripe()
        ->create(
            [
                'external_id' => data_get($payload, 'data.object.id'),
            ],
        );

    $stripeSignature = generateStripeSignature(json_encode($payload), config('faktura.stripe.webhook_secret'));

    $this->withHeaders(
        [
            'Stripe-Signature' => $stripeSignature,
        ],
    )
        ->postJson(
            route('faktura::callback:stripe'),
            $payload,
        )
        ->assertStatus(200);

    Bus::assertDispatched(SyncInvoiceJob::class, function (SyncInvoiceJob $job) use ($invoice) {
        return $job->invoiceId === $invoice->id && $job->invoiceDTO->externalId === $invoice->external_id;
    });
});

it('does not process webhooks with invalid secret', function () {
    $oldSecret = 'whsec_test_wrong_secret';
    Config::set('faktura.stripe.webhook_secret', 'whsec_test_secret');

    // Load the fixture for checkout success.
    $payload = $this->loadFixture('stripe-invoice-paid');

    // Create an invoice with empty lines
    $invoice = InvoiceFactory::new()
        ->stripe()
        ->create(
            [
                'external_id' => data_get($payload, 'data.object.id'),
            ],
        );

    $originalUpdatedAt = $invoice->updated_at;

    expect($invoice->status)->toBe(Status::Draft)->and($invoice->lines)->toBeEmpty();

    $stripeSignature = generateStripeSignature(json_encode($payload), $oldSecret);

    $this->withHeaders(
        [
            'Stripe-Signature' => $stripeSignature,
        ],
    )
        ->postJson(
            route('faktura::callback:stripe'),
            $payload,
        )
        ->assertStatus(400);

    $invoice->refresh();

    expect($invoice->status)->toBe(Status::Draft)
        ->and($invoice->lines)->toBeEmpty()
        ->and($invoice->updated_at->timestamp)->toBe($originalUpdatedAt->timestamp);
});

it('does not process an invoice that does not exist in the system', function () {
    Config::set('faktura.stripe.webhook_secret', 'whsec_test_secret');

    // Load the fixture for checkout success.
    $payload = $this->loadFixture('stripe-invoice-paid');

    $stripeSignature = generateStripeSignature(json_encode($payload), config('faktura.stripe.webhook_secret'));

    $this->withHeaders(
        [
            'Stripe-Signature' => $stripeSignature,
        ],
    )
        ->postJson(
            route('faktura::callback:stripe'),
            $payload,
        )
        ->assertStatus(400);

    expect(Invoice::query()->where('external_id', data_get($payload, 'data.object.id'))->first())->toBeNull();
});

if (! function_exists('generateStripeSignature')) {
    /**
     * Generate a Stripe webhook signature for testing.
     *
     * @param string $payload Raw JSON payload
     * @param string $secret Webhook signing secret
     * @param int|null $timestamp Optional timestamp (defaults to current time)
     * @return string Stripe-Signature header value
     */
    function generateStripeSignature(string $payload, string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();

        $signedPayload = $timestamp . '.' . $payload;
        $signature = hash_hmac('sha256', $signedPayload, $secret);

        return "t={$timestamp},v1={$signature}";
    }
}
