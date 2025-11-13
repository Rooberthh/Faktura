<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

use Illuminate\Support\Carbon;

readonly class Deleted
{
    public function __construct() {
        //
    }

    public function fromArray(): Deleted
    {
        return new self();
    }
}
