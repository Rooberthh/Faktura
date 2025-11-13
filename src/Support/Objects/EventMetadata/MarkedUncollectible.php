<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

use Illuminate\Support\Carbon;

readonly class MarkedUncollectible
{
    public function __construct() {
        //
    }

    public function fromArray(): MarkedUncollectible
    {
        return new self();
    }
}
