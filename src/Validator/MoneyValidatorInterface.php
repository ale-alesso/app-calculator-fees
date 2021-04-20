<?php

namespace App\Validator;

use App\Entity\Money;

interface MoneyValidatorInterface
{
    public function validate(Money $money): void;
}
