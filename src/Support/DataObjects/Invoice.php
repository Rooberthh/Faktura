<?php

namespace Rooberthh\Faktura\Support\DataObjects;

use Illuminate\Support\Collection;
use Rooberthh\Faktura\Support\Enums\Provider;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Buyer;
use Rooberthh\Faktura\Support\Objects\Price;
use Rooberthh\Faktura\Support\Objects\Seller;
use Stripe\Invoice as StripeInvoice;

readonly class Invoice
{
    /**
     * @param string $externalId
     * @param Provider $provider
     * @param Status $status
     * @param Price $total
     * @param Buyer $buyer
     * @param Seller $seller
     * @param Collection<InvoiceLine>  $lines
    */
    public function __construct(
        public string $externalId,
        public Provider $provider,
        public Status $status,
        public Price $total,
        public Collection $lines,
    ) {
        //
    }

    public static function fromStripeInvoice(StripeInvoice $invoice): self
    {
        $lines = collect($invoice->lines->data)->map(function ($line) {
            return InvoiceLine::fromStripeInvoiceLineItem($line);
        });

        return new self(
            externalId: $invoice->id,
            provider: Provider::STRIPE,
            status: Status::from($invoice->status),
            total: Price::fromMinor($invoice->total),
            lines: $lines,
        );
    }
}
