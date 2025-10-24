<?php

namespace Rooberthh\Faktura\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rooberthh\Faktura\Models\InvoiceLine;
use Rooberthh\Faktura\Support\Objects\Price;

/**
 * @extends Factory<InvoiceLine>
 */
class InvoiceLineFactory extends Factory
{
    protected $model = InvoiceLine::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $priceInMajor = $this->faker->numberBetween(10, 2800);
        $vatRate = $this->faker->randomElement([0, 6, 12, 25]);

        $priceIncVatCents = bcmul((string) $priceInMajor, "100");

        $priceExVat = ($vatRate > 0)
            ? intdiv((int) $priceIncVatCents, (100 + $vatRate)) * 100
            : $priceIncVatCents;

        $quantity = $this->faker->numberBetween(1, 3);

        return [
            'invoice_id' => InvoiceFactory::new(),
            'sku' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'quantity' => $quantity,
            'unit_price_inc_vat' => Price::fromMinor($priceIncVatCents / $quantity),
            'unit_price_ex_vat' => Price::fromMinor($priceExVat / $quantity),
            'unit_vat_amount' => Price::fromMinor(($priceIncVatCents - $priceExVat) / $quantity),
            'vat_rate' => $vatRate,
            'sub_total' => Price::fromMinor($priceExVat),
            'vat_total' => Price::fromMinor($priceIncVatCents - $priceExVat),
            'total' => Price::fromMinor($priceIncVatCents),
            'metadata' => null,
        ];
    }
}
