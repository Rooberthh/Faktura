<?php

namespace Rooberthh\Faktura\Support\Enums;

enum Status: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Paid = 'paid';
    case Uncollectible = 'uncollectible';
    case Void = 'void';
}
