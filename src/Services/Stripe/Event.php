<?php

namespace Rooberthh\Faktura\Services\Stripe;

enum Event: string
{
    case INVOICE_PAID = 'invoice.paid';
    case INVOICE_VOID = 'invoice.voided';
    case INVOICE_PAYMENT_FAILED = 'invoice.payment_failed';
    case INVOICE_FINALIZED = 'invoice.finalized';
    case INVOICE_DELETED = 'invoice.deleted';
}
