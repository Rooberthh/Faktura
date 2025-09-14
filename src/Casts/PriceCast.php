<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Price;

class PriceCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return Price::fromMinor($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (! $value instanceof Price) {
            throw new InvalidArgumentException("The given value is not a Money instance.");
        }

        return [
            $key => $value->amount(),
        ];
    }
}
