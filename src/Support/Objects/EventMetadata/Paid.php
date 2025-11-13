<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class Paid
{
    public function __construct() {
        //
    }

    public function fromArray(): Paid
    {
        return new self();
    }
}
