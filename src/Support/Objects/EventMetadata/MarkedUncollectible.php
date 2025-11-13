<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class MarkedUncollectible
{
    public function __construct()
    {
        //
    }

    public function fromArray(): MarkedUncollectible
    {
        return new self();
    }
}
