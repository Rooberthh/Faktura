<?php

namespace Rooberthh\Faktura\Actions;

use Rooberthh\Faktura\Contracts\Billable;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Seller;

class StartInvoiceAction
{
    public function execute(Billable $billable, array $lines): Invoice
    {
        $invoice = Invoice::query()->make();
        $invoice->number = 'my-test-number';
        $invoice->status = Status::Draft;
        $invoice->billable()->associate($billable);
        $invoice->buyer = $billable->toBuyer();
        $invoice->seller = new Seller(
            name: 'Default',
            address: 'test',
            city: 'test',
            postalCode: '54135',
            country: 'sweden',
            orgNumber: '199803199570',
            vatNumber: '199803199570',
            iban: 'my-iban-number',
        );

        foreach ($lines as $line) {
            $invoice->addLine($line);
        }

        $invoice->save();
        $invoice->lines()->saveMany($invoice->lines->all());

        $invoice->recalculateTotals();

        return $invoice->refresh();
    }
}
