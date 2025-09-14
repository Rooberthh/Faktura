<?php

namespace Rooberthh\Faktura\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Price;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => Invoice::query()->count() + 1,
            'status' => Status::Draft,
            'billable_id' => null,
            'billable_type' => null,
            'billing_name' => $this->faker->name(),
            'billing_address' => $this->faker->address(),
            'billing_postal_code' => $this->faker->postcode(),
            'billing_city' => $this->faker->city(),
            'billing_country' => $this->faker->country(),
            'billing_org_number' => $this->faker->bankAccountNumber(),
            'billing_vat_number' => $this->faker->bankAccountNumber(),
            'seller_name' => $this->faker->name(),
            'seller_address' => $this->faker->address(),
            'seller_postal_code' => $this->faker->postcode(),
            'seller_city' => $this->faker->city(),
            'seller_country' => $this->faker->country(),
            'seller_org_number' => $this->faker->bankAccountNumber(),
            'seller_vat_number' => $this->faker->bankAccountNumber(),
            'seller_iban' => $this->faker->iban(),
            'seller_payment_reference' => $this->faker->unique()->text(),
            'total' => Price::fromMinor($this->faker->numberBetween(10000, 100000)),
            'provider' => null,
            'external_id' => null,
            'metadata' => null,
        ];
    }
}
