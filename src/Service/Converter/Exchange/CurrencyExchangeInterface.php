<?php

namespace App\Service\Converter\Exchange;

use App\Exception\MissedConversionRateException;

interface CurrencyExchangeInterface
{
    /**
     * @param string $from
     * @param string $to
     * @return float
     * @throws MissedConversionRateException
     */
    public function getRate(string $from, string $to): float;
}
