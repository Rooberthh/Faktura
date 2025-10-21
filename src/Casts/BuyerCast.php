<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Buyer;

class BuyerCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Buyer
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

    public function set($model, string $key, $value, array $attributes): null|array
    {
        if (is_null($value)) {
            return null; // Handle null values
        }

        if (! $value instanceof Buyer) {
            throw new InvalidArgumentException("Invalid buyer data");
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
