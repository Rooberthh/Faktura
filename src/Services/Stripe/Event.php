<?php

namespace Rooberthh\Faktura\Services\Stripe;

enum Event: string
{
    case INVOICE_PAID = 'invoice.paid';
    case INVOICE_VOIDED = 'invoice.voided';
    case INVOICE_MARKED_UNCOLLECTIBLE = 'invoice.marked_uncollectible';
    case INVOICE_FINALIZED = 'invoice.finalized';
    case INVOICE_DELETED = 'invoice.deleted';
}
