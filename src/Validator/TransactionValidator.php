<?php

namespace App\Validator;

use App\Entity\Transaction;
use App\Exception\InvalidTransactionException;

class TransactionValidator implements TransactionValidatorInterface
{
    private MoneyValidatorInterface $moneyValidator;
    private UserValidatorInterface $userValidator;

    public function __construct(MoneyValidatorInterface $moneyValidator, UserValidatorInterface $userValidator)
    {
        $this->moneyValidator = $moneyValidator;
        $this->userValidator = $userValidator;
    }

    /**
     * @param Transaction $transaction
     * @throws InvalidTransactionException
     */
    public function validate(Transaction $transaction): void
    {
        if ($transaction->getCreatedAt() === null) {
            throw new InvalidTransactionException('Operation date is not set');
        }

        if ($transaction->getType() === null) {
            throw new InvalidTransactionException('Operation type is not set');
        }

        if (!in_array($transaction->getType(), Transaction::$types, true)) {
            throw new InvalidTransactionException('Unknown transaction type: ' . $transaction->getType());
        }

        $this->userValidator->validate($transaction->getUser());
        $this->moneyValidator->validate($transaction->getMoney());
    }
}
