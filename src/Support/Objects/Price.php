<?php

namespace Rooberthh\Faktura\Support\Objects;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

final class Price
{
    public function __construct(
        private Money $money,
    ) {}

    public static function fromMinor(int $amount, string $currency = 'SEK'): self
    {
        return new self(new Money($amount, new Currency($currency)));
    }

    public function money(): Money
    {
        return $this->money;
    }

    public function format(string $locale = 'sv_SE'): string
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $formatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $formatter->format($this->money);
    }

    public function amount(): int
    {
        return (int) $this->money->getAmount(); // minor units
    }

    public function currency(): string
    {
        return $this->money->getCurrency()->getCode();
    }
}
