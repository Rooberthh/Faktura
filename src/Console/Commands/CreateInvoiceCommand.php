<?php

namespace Rooberthh\Faktura\Console\Commands;

use Illuminate\Console\Command;
use Rooberthh\Faktura\Database\Factories\InvoiceFactory;
use Rooberthh\Faktura\Database\Factories\InvoiceLineFactory;
use Rooberthh\Faktura\Services\Stripe\StripeGateway;
use Stripe\StripeClient;

class CreateInvoiceCommand extends Command
{
    protected $signature = 'faktura:create-invoice';

    protected $description = 'Create a test invoice into stripe';

    public function handle(): void
    {
        $invoice = InvoiceFactory::new()
            ->has(InvoiceLineFactory::new()->count(2), 'lines')
            ->stripe()
            ->create(
                [
                    'billing_external_id' => 'cus_SF5PGh60JEglp4',
                ],
            );

        $client = new StripeClient(config('faktura.stripe.api_key'));

        $stripeGateway = new StripeGateway($client);

        $stripeGateway->createInvoice($invoice);
    }
}
