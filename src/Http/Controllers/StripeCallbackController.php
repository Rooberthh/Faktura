<?php

namespace Rooberthh\Faktura\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Services\Stripe\Event;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\Enums\Provider;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class StripeCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('faktura.stripe.webhook_secret'),
            );

            $internalEvent = Event::from($event->type);

            if ($internalEvent === Event::INVOICE_PAID) {
                $invoiceDTO = InvoiceDTO::fromStripeInvoice($event->data->object);

                /** @var Invoice|null $invoice */
                $invoice = Invoice::query()
                    ->provider(Provider::STRIPE)
                    ->where('external_id', $invoiceDTO->externalId)
                    ->first();

                if (! $invoice) {
                    return response('Invalid invoice_id', 400);
                }

                $invoice->syncFromDto($invoiceDTO);
            }
        } catch (SignatureVerificationException|UnexpectedValueException $e) {
            report($e);

            return response('Invalid webhook', 400);
        }
    }
}
