<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Seller;

class SellerCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Seller
    {
        return new Seller(
            name: $attributes['seller_name'],
            address: $attributes['seller_address'],
            city: $attributes['seller_city'],
            postalCode: $attributes['seller_postal_code'],
            country: $attributes['seller_country'],
            orgNumber: $attributes['seller_org_number'],
            vatNumber: $attributes['seller_vat_number'],
            iban: $attributes['seller_iban'],
        );
    }

    public function set($model, string $key, $value, array $attributes): null|array
    {
        if (is_null($value)) {
            return null; // Handle null values
        }

        if (! $value instanceof Seller) {
            throw new InvalidArgumentException("Invalid seller data");
        }

        return [
            'seller_name' => $value->name,
            'seller_address' => $value->address,
            'seller_city' => $value->city,
            'seller_postal_code' => $value->postalCode,
            'seller_country' => $value->country,
            'seller_org_number' => $value->orgNumber,
            'seller_vat_number' => $value->vatNumber,
            'seller_iban' => $value->iban,
        ];
    }
}
