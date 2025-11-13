<?php

namespace Rooberthh\Faktura\Support\Objects\EventMetadata;

readonly class PaymentFailed
{
    public function __construct()
    {
        //
    }

    public function fromArray(): PaymentFailed
    {
        return new self();
    }
}
