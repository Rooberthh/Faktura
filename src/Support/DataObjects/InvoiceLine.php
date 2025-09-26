<?php

namespace Rooberthh\Faktura\Support\DataObjects;

use Rooberthh\Faktura\Support\Objects\Price;
use Stripe\InvoiceLineItem;

readonly class InvoiceLine
{
    public function __construct(
        public string $sku,
        public string $description,
        public int $quantity,
        public Price $unitPriceExVat,
        public Price $unitVatAmount,
        public Price $unitPriceIncVat,
        public int $vatRate,
        public Price $subTotal,
        public Price $vatTotal,
        public Price $total,
        public array $metadata,
    ) {
        //
    }

    public static function fromStripeInvoiceLineItem(InvoiceLineItem $lineItem): self
    {
        $quantity = $lineItem->quantity;

        $total = Price::fromMinor($lineItem->amount);
        $subTotal = Price::fromMinor($lineItem->amount_excluding_tax);
        $vatTotal = $total->money()->subtract($subTotal->money());

        $unitPriceExVat = $subTotal->money()->divide($quantity);
        $unitPriceIncVat = $total->money()->divide($quantity);
        $unitVatAmount = $vatTotal->divide($quantity);

        $vatRate = ($vatTotal->getAmount() / $subTotal->money()->getAmount()) * 100;

        return new self(
            sku: $lineItem->metadata['sku'],
            description: $lineItem->description,
            quantity: $quantity,
            unitPriceExVat: Price::fromMinor((int) $unitPriceExVat->getAmount()),
            unitVatAmount: Price::fromMinor((int) $unitVatAmount->getAmount()),
            unitPriceIncVat: Price::fromMinor((int) $unitPriceIncVat->getAmount()),
            vatRate: $vatRate,
            subTotal: $subTotal,
            vatTotal: Price::fromMinor((int) $vatTotal->getAmount()),
            total: $total,
            metadata: $lineItem->toArray(),
        );
    }
}
