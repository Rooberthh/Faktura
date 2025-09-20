<?php

namespace Rooberthh\Faktura\Services;

use Exception;
use Rooberthh\Faktura\Contracts\GatewayContract;

class InMemoryGateway implements GatewayContract
{
    public function createInvoice()
    {
        throw new Exception('Not implemented');
    }

    public function handleCallback()
    {
        throw new Exception('Not implemented');
    }
}
