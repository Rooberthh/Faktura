<?php

namespace Rooberthh\Faktura\Support\Objects;

final class Buyer
{
    public function __construct(
        public string $name,
        public string $address,
        public string $city,
        public string $postalCode,
        public string $country,
        public string $orgNumber,
        public string $vatNumber,
        public string $externalId,
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
            'external_id' => $this->externalId,
        ];
    }
}
