<?php

namespace Rooberthh\Faktura\Support\Enums;

enum VatRate: int
{
    case Zero = 0;
    case Six = 6;
    case Twelve = 12;
    case TwentyFive = 25;
}
