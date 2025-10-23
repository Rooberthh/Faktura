<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Buyer;

/**
 * @implements CastsAttributes<Buyer, array<string, mixed>|null>
 */
class BuyerCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Buyer
    {
        return new Buyer(
            name: $attributes['billing_name'],
            address: $attributes['billing_address'],
            city: $attributes['billing_city'],
            postalCode: $attributes['billing_postal_code'],
            country: $attributes['billing_country'],
            orgNumber: $attributes['billing_org_number'],
            vatNumber: $attributes['billing_vat_number'],
            externalId: $attributes['billing_external_id'],
        );
    }

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @return null|array{billing_name: string, billing_address: string, billing_city: string, billing_postal_code: string, billing_country: string, billing_org_number: string, billing_vat_number: string, billing_external_id: string}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value)) {
            return null; // Handle null values
        }

        if (! $value instanceof Buyer) {
            throw new InvalidArgumentException('Invalid buyer data');
        }

        return [
            'billing_name' => $value->name,
            'billing_address' => $value->address,
            'billing_city' => $value->city,
            'billing_postal_code' => $value->postalCode,
            'billing_country' => $value->country,
            'billing_org_number' => $value->orgNumber,
            'billing_vat_number' => $value->vatNumber,
            'billing_external_id' => $value->externalId,
        ];
    }
}
