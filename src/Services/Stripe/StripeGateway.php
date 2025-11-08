<?php

namespace Rooberthh\Faktura\Services\Stripe;

use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\Invoice as InvoiceDTO;
use Rooberthh\Faktura\Support\Enums\Provider;
use Stripe\StripeClient;

class StripeGateway implements GatewayContract
{
    public function __construct(public StripeClient $client)
    {
        //
    }

    public function get(string $externalId): InvoiceDTO
    {
        $stripeInvoice = $this->client->invoices->retrieve($externalId);

        return InvoiceDTO::fromStripeInvoice($stripeInvoice);
    }

    public function createInvoice(Invoice $invoice): InvoiceDTO
    {
        $stripeInvoice = $this->client->invoices->create(
            [
                'auto_advance' => false,
                'customer' => $invoice->billable->getExternalId(),
            ]
        );

        foreach ($invoice->lines as $line) {
            $this->client->invoiceItems->create(
                [
                    'invoice'             => $stripeInvoice->id,
                    'customer'            => $invoice->billable->getExternalId(),
                    'currency'            => 'SEK',
                    'description'         => $line->description,
                    'quantity'            => $line->quantity,
                    'unit_amount_decimal' => (string) $line->unit_price_inc_vat->amount(),
                    'tax_behavior'        => 'inclusive',
                    'metadata'            => [
                        'sku' => $line->sku,
                    ],
                ],
            );
        }

        $invoice->external_id = $stripeInvoice->id;
        $invoice->provider = Provider::STRIPE;
        $invoice->save();

        return InvoiceDTO::fromInvoice($invoice);
    }

    /**
     * @param  Invoice  $invoice
     * @return array{customer: string, auto_advance: boolean}
     */
    protected function getInvoicePayload(Invoice $invoice): array
    {
        return [
            'customer'     => 'cus_SF5PGh60JEglp4',
            'auto_advance' => false,
        ];
    }
}
