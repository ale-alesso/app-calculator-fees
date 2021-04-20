<?php

namespace App\Service\Converter;

use App\Entity\Money;

interface CurrencyConverterInterface
{
    public function convert(Money $money, string $currency): Money;
}
