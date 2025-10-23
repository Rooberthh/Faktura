<?php

namespace Rooberthh\Faktura\Support\DataObjects;

use Illuminate\Support\Collection;
use Rooberthh\Faktura\Models\Invoice as InvoiceModel;
use Rooberthh\Faktura\Models\InvoiceLine;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine as InvoiceLineDTO;
use Rooberthh\Faktura\Support\Enums\Provider;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Price;
use Stripe\Invoice as StripeInvoice;

readonly class Invoice
{
    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @param  string                        $externalId
     * @param  Provider                      $provider
     * @param  Status                        $status
     * @param  Price                         $total
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

    public static function fromInvoice(InvoiceModel $invoice): self
    {
        return new Invoice(
            externalId: $invoice->external_id,
            provider: $invoice->provider,
            status: $invoice->status,
            total: $invoice->total,
            lines: $invoice->lines->map(function (InvoiceLine $line) {
                return new InvoiceLineDTO(
                    sku: $line->sku,
                    description: $line->description,
                    quantity: $line->quantity,
                    unitPriceExVat: $line->unit_price_ex_vat,
                    unitVatAmount: $line->unit_vat_amount,
                    unitPriceIncVat: $line->unit_price_inc_vat,
                    vatRate: $line->vat_rate,
                    subTotal: $line->sub_total,
                    vatTotal: $line->vat_total,
                    total: $line->total,
                    metadata: [],
                );
            }),
        );
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
