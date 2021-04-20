<?php

namespace App\Service\Calculator;

use App\Entity\Transaction;
use App\Entity\Money;

interface CalculatorInterface
{
    public function calculate(Transaction $transaction): Money;
}
