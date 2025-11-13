<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

use Illuminate\Support\Carbon;

readonly class PaymentFailed
{
    public function __construct() {
        //
    }

    public function fromArray(): PaymentFailed
    {
        return new self();
    }
}
