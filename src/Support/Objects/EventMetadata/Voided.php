<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

use Illuminate\Support\Carbon;

readonly class Voided
{
    public function __construct() {
        //
    }

    public function fromArray(): Voided
    {
        return new self();
    }
}
