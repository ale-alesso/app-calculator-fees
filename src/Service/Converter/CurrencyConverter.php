<?php

namespace App\Service\Converter;

use App\Exception\MissedConversionRateException;
use App\Service\Converter\Exchange\CurrencyExchangeInterface;
use App\Entity\Money;

class CurrencyConverter implements CurrencyConverterInterface
{
    private CurrencyExchangeInterface $exchange;

    public function __construct(CurrencyExchangeInterface $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * @param Money $money
     * @param string $currency
     * @return Money
     * @throws MissedConversionRateException
     */
    public function convert(Money $money, string $currency): Money
    {
        if ($money->getCurrency() === $currency) {
            return Money::factory($money->getAmount(), $money->getCurrency());
        }

        $exchangeRate = $this->exchange->getRate($money->getCurrency(), $currency);

        return $money->multiply($exchangeRate)->setCurrency($currency);
    }
}
