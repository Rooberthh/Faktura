<?php

namespace Rooberthh\Faktura\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Rooberthh\Faktura\Support\Enums\EventType;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Deleted;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Finalized;
use Rooberthh\Faktura\Support\Objects\EventMetadata\MarkedUncollectible;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Paid;
use Rooberthh\Faktura\Support\Objects\EventMetadata\PaymentFailed;
use Rooberthh\Faktura\Support\Objects\EventMetadata\Voided;

/**
 * @implements CastsAttributes<Paid|Voided|MarkedUncollectible|Finalized|Deleted|PaymentFailed|array|null, array<string, mixed>|null>
 */
class EventMetadataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Paid|Voided|MarkedUncollectible|Finalized|Deleted|PaymentFailed|array|null
    {
        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true);

        if (! isset($attributes['type'])) {
            return $data;
        }

        $eventType = EventType::from($attributes['type']);

        return match ($eventType) {
            EventType::INVOICE_PAID => Paid::fromArray($data),
            EventType::INVOICE_VOIDED => Voided::fromArray($data),
            EventType::INVOICE_MARKED_UNCOLLECTIBLE => MarkedUncollectible::fromArray($data),
            EventType::INVOICE_FINALIZED => Finalized::fromArray($data),
            EventType::INVOICE_DELETED => Deleted::fromArray($data),
            EventType::INVOICE_PAYMENT_FAILED => PaymentFailed::fromArray($data),
        };
    }

    /**
     * @return array<string, string|null>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        if (is_array($value)) {
            return [$key => json_encode($value)];
        }

        if (
            $value instanceof Paid ||
            $value instanceof Voided ||
            $value instanceof MarkedUncollectible ||
            $value instanceof Finalized ||
            $value instanceof Deleted ||
            $value instanceof PaymentFailed
        ) {
            return [$key => json_encode($value->toArray())];
        }

        throw new InvalidArgumentException('The given value is not a valid event metadata instance or array.');
    }
}
