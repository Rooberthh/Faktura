<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class Voided
{
    public function __construct()
    {
        //
    }

    public function fromArray(): Voided
    {
        return new self();
    }
}
