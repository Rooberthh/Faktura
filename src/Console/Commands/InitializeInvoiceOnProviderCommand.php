<?php

namespace Rooberthh\Faktura\Console\Commands;

use Illuminate\Console\Command;
use Rooberthh\Faktura\Jobs\InitializeInvoiceOnProviderJob;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\Enums\Provider;

class InitializeInvoiceOnProviderCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'faktura:initialize-invoice {id : The ID of the invoice}';

    /**
     * The console command description.
     */
    protected $description = 'Dispatch a job to initialize an invoice on its provider';

    public function handle(): int
    {
        $invoice = Invoice::query()->findOrFail($this->argument('id'));

        $provider = $this->choice(
            'Which provider do you want to use?',
            array_map(fn($case) => $case->value, Provider::cases()),
        );

        $invoice->provider = $provider;
        $invoice->save();

        // Dispatch the job
        InitializeInvoiceOnProviderJob::dispatch($invoice->id);

        $this->info("Invoice initialization job dispatched for ID {$invoice->id}.");

        return self::SUCCESS;
    }
}
