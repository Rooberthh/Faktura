<?php

namespace Rooberthh\Faktura\Actions;

use Illuminate\Database\Eloquent\Model;
use Rooberthh\Faktura\Contracts\Billable;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\DataObjects\InvoiceLine;
use Rooberthh\Faktura\Support\Enums\Status;
use Rooberthh\Faktura\Support\Objects\Seller;

class StartInvoiceAction
{
    /**
     * @param  Billable&Model $billable
     * @param  InvoiceLine[] $lines
     * @return Invoice
     */
    public function execute(Billable $billable, Seller $seller, array $lines): Invoice
    {
        $invoice = Invoice::query()->make();
        $invoice->number = 'my-test-number';
        $invoice->status = Status::Draft;
        $invoice->billable()->associate($billable);
        $invoice->buyer = $billable->toBuyer();
        $invoice->seller = $seller;

        foreach ($lines as $line) {
            $invoice->addLine($line);
        }

        $invoice->save();
        $invoice->lines()->saveMany($invoice->lines->all());

        $invoice->recalculateTotals();

        return $invoice;
    }
}
