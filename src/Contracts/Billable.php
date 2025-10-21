<?php

namespace Rooberthh\Faktura\Contracts;

use Rooberthh\Faktura\Support\Objects\Buyer;

interface Billable
{
    public function toBuyer(): Buyer;

    public function getExternalId(): ?string;
}
