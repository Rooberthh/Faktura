<?php

namespace Rooberthh\Faktura\Support\Enums;

enum Provider: string
{
    case IN_MEMORY = 'in_memory';
    case STRIPE = 'stripe';
}
