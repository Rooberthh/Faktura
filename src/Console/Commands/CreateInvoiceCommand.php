<?php

namespace Rooberthh\Faktura\Console\Commands;

use Illuminate\Console\Command;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Services\Stripe\StripeGateway;
use Stripe\StripeClient;

class CreateInvoiceCommand extends Command
{
    protected $signature = 'faktura:create-invoice';

    protected $description = 'Create a test invoice into stripe';

    public function handle(): void
    {
        $invoice = Invoice::query()->first();

        $client = new StripeClient(config('faktura.stripe.api_key'));

        $stripeGateway = new StripeGateway($client);

        $stripeGateway->createInvoice($invoice);
    }
}
