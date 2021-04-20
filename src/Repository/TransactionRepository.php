<?php

namespace App\Repository;

use App\Entity\Transaction;

class TransactionRepository
{
    private array $transactions;

    /**
     * @param Transaction[] $transactions
     */
    public function __construct(array $transactions = [])
    {
        $this->transactions = $transactions;
    }

    public function add(Transaction $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    public function findByPeriod(Transaction $transaction, string $scope = 'oW'): array
    {
        $transactionType = $transaction->getType();
        $period = $transaction->getCreatedAt()->format($scope);
        $user = $transaction->getUser();

        return array_filter($this->transactions, function (Transaction $transaction) use ($transactionType, $user, $period, $scope) {
            return
                $transaction->getType() === $transactionType
                && $transaction->getCreatedAt()->format($scope) === $period
                && $transaction->getUser()->getId() === $user->getId()
                && $transaction->getUser()->getType() === $user->getType()
            ;
        });
    }
}
