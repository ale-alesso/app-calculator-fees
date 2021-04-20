<?php

namespace App\Validator;

use App\Exception\InvalidMoneyException;
use App\Exception\UnknownCurrencyException;
use App\Entity\Money;

class MoneyValidator implements MoneyValidatorInterface
{
    /**
     * @var string[]
     */
    private array $currencies;

    /**
     * @param string[] $currencies
     */
    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;
    }

    /**
     * @param Money $money
     * @throws InvalidMoneyException
     * @throws UnknownCurrencyException
     */
    public function validate(Money $money): void
    {
        if ($money->getAmount() <= 0) {
            throw new InvalidMoneyException('Amount is equal or less then zero: ' . $money->getAmount());
        }

        if (!in_array($money->getCurrency(), $this->currencies, true)) {
            throw new UnknownCurrencyException('Currency is unknown: ' . $money->getCurrency());
        }
    }
}
