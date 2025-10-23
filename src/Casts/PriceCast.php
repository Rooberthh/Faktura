<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Objects\Price;

/**
 * @implements CastsAttributes<Price, array<string, mixed>|null>
 */
class PriceCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Price
    {
        if ($value === null) {
            return null;
        }

        return Price::fromMinor($value);
    }

    /**
     * @return array<string, int>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof Price) {
            throw new InvalidArgumentException('The given value is not a Money instance.');
        }

        return [
            $key => $value->amount(),
        ];
    }
}
