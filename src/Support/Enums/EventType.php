<?php

namespace Rooberthh\Faktura\Support\Enums;

use Rooberthh\Faktura\Services\Stripe\Event;
use Rooberthh\Faktura\Services\Stripe\Event as StripeEvent;

enum EventType: string {
    case INVOICE_PAID = 'invoice.paid';
    case INVOICE_VOIDED = 'invoice.voided';
    case INVOICE_MARKED_UNCOLLECTIBLE = 'invoice.marked_uncollectible';
    case INVOICE_FINALIZED = 'invoice.finalized';
    case INVOICE_DELETED = 'invoice.deleted';
    case INVOICE_PAYMENT_FAILED = 'invoice.payment_failed';
    case INVOICE_SENT = 'invoice.sent';
    case INVOICE_ITEM_CREATED = 'invoiceitem.created';
    case INVOICE_ITEM_DELETED= 'invoiceitem.deleted';

    public static function fromStripeEvent(StripeEvent $stripeEvent): EventType
    {
        return match ($stripeEvent) {
            StripeEvent::INVOICE_PAID => EventType::INVOICE_PAID,
            StripeEvent::INVOICE_VOIDED => EventType::INVOICE_VOIDED,
            StripeEvent::INVOICE_DELETED => EventType::INVOICE_DELETED,
            StripeEvent::INVOICE_FINALIZED => EventType::INVOICE_FINALIZED,
            StripeEvent::INVOICE_MARKED_UNCOLLECTIBLE => EventType::INVOICE_MARKED_UNCOLLECTIBLE,
            StripeEvent::INVOICE_PAYMENT_FAILED => EventType::INVOICE_PAYMENT_FAILED,
            StripeEvent::INVOICE_SENT => EventType::INVOICE_SENT,
            StripeEvent::INVOICE_ITEM_CREATED => EventType::INVOICE_ITEM_CREATED,
            StripeEvent::INVOICE_ITEM_DELETED => EventType::INVOICE_ITEM_DELETED,
        };
    }
}
