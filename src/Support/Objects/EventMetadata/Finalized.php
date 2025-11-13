<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class Finalized
{
    public function __construct()
    {
        //
    }

    public function fromArray(): Finalized
    {
        return new self();
    }
}
