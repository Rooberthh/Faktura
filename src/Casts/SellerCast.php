<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Seller;

/**
 * @implements CastsAttributes<Seller, array<string, mixed>|null>
 */
class SellerCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Seller
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

    /**
     * @return null|array{seller_name: string, seller_address: string, seller_city: string, seller_postal_code: string, seller_country:string, seller_org_number: string, seller_vat_number: string, seller_iban: string}
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if (is_null($value)) {
            return null; // Handle null values
        }

        if (! $value instanceof Seller) {
            throw new InvalidArgumentException('Invalid seller data');
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
