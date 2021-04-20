<?php

namespace App\Validator;

use App\Entity\Transaction;

interface TransactionValidatorInterface
{
    public function validate(Transaction $transaction): void;
}
