<?php

namespace App\Entity;

use App\Exception\InvalidMoneyException;

class Money
{
    private static array $precisions = [
	'EUR' => 2,
	'USD' => 2,
	'JPY' => 0,
	// ..
    ];

    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency)
    {
	$this->amount = $amount;
	$this->currency = $currency;
    }

    public static function factory(float $amount, string $currency): Money
    {
	return new static($amount, $currency);
    }

    public function getAmount(): float
    {
	return $this->amount;
    }

    public function getFormattedAmount(): string
    {
	return $this->amount === 0 ? '0' : number_format($this->amount, $this->getPrecision(), '.', '');
    }

    public function setCurrency(string $currency): Money
    {
	$this->currency = $currency;

	return $this;
    }

    public function getCurrency(): string
    {
	return $this->currency;
    }

    public function add(Money $money): Money
    {
	if ($this->currency !== $money->getCurrency()) {
	    throw new InvalidMoneyException(sprintf("Cannot add money of different currencies: %s to %s)", $this->getCurrency(), $money->getCurrency()));
	}

	$this->amount += $money->getAmount();

	return $this;
    }

    public function multiply(float $multiplier): Money
    {
	$this->amount *= $multiplier;

	return $this;
    }

    public function subtract(Money $money): Money
    {
	if ($this->currency !== $money->getCurrency()) {
	    throw new InvalidMoneyException(sprintf("Cannot subtract money of different currencies: %s from %s)", $money->getCurrency(), $this->getCurrency()));
	}

	$multiplier = $this->getMultiplier();
	$this->amount = (floor($this->amount * $multiplier) - floor($money->getAmount() * $multiplier)) / $multiplier;

	return $this;
    }

    public function ceil(): Money
    {
	$multiplier = $this->getMultiplier();
	$this->amount = ceil($this->amount * $multiplier) / $multiplier;

	return $this;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function isGreater(Money $money): bool
    {
	if ($this->currency !== $money->getCurrency()) {
	    throw new InvalidMoneyException(sprintf("Cannot compare money of different currencies: %s to %s)", $this->getCurrency(), $money->getCurrency()));
	}

	return floor($this->getAmount() * $this->getMultiplier()) - floor($money->getAmount() * $this->getMultiplier()) > 0;
    }

    private function getPrecision(): int
    {
        return self::$precisions[$this->getCurrency()];
    }

    private function getMultiplier(): int
    {
        return pow(10, $this->getPrecision());
    }
}