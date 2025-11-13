<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class Deleted
{
    public function __construct()
    {
        //
    }

    public function fromArray(): Deleted
    {
        return new self();
    }
}
