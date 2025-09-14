<?php

namespace Rooberthh\Faktura\Support\Objects;

final class Seller
{
    public function __construct(
        public string $name,
        public string $address,
        public string $city,
        public string $postalCode,
        public string $country,
        public string $orgNumber,
        public string $vatNumber,
        public string $iban,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'org_number' => $this->orgNumber,
            'vat_number' => $this->vatNumber,
            'iban' => $this->iban,
        ];
    }
}
