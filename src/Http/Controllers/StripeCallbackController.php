<?php

namespace Rooberthh\Faktura\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Rooberthh\Faktura\Actions\RecordEventAction;
use Rooberthh\Faktura\Jobs\SyncInvoiceJob;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Services\Stripe\Event;
use Rooberthh\Faktura\Support\Enums\EventType;
use Rooberthh\Faktura\Support\Enums\Provider;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class StripeCallbackController extends Controller
{
    public function __invoke(Request $request, RecordEventAction $recordEventAction): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('faktura.stripe.webhook_secret'),
            );

            $stripeEvent = Event::tryFrom($event->type);

            // If we should not respond to event just say ok.
            if (! $stripeEvent) {
                return response('Ok', 200);
            }

            /** @var Invoice|null $invoice */
            $invoice = Invoice::query()
                ->provider(Provider::STRIPE)
                ->where('external_id', $event->data->object->id)
                ->first();

            if (! $invoice) {
                return response('Invalid invoice_id', 400);
            }

            $eventType = EventType::fromStripeEvent($stripeEvent);

            if ($eventType) {
                $recordEventAction->execute($invoice, $eventType);
            }

            dispatch(new SyncInvoiceJob($invoice->id));

            return response('Ok', 200);
        } catch (SignatureVerificationException|UnexpectedValueException $e) {
            report($e);

            return response('Invalid webhook', 400);
        }
    }
}
