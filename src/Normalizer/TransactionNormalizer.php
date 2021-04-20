<?php

namespace App\Normalizer;

use App\Entity\Transaction;
use App\Exception\InvalidMappingException;

class TransactionNormalizer
{
    private MoneyNormalizer $moneyNormalizer;
    private UserNormalizer $userNormalizer;

    public function __construct(MoneyNormalizer $moneyNormalizer, UserNormalizer $userNormalizer)
    {
        $this->moneyNormalizer = $moneyNormalizer;
        $this->userNormalizer = $userNormalizer;
    }

    /**
     * @param array $data
     * @return Transaction
     * @throws InvalidMappingException
     */
    public function toObject(array $data): Transaction
    {
	$user = $this->userNormalizer->toObject($data);
	$money = $this->moneyNormalizer->toObject($data);

        if (!isset($data['operation_date'])) {
            throw new InvalidMappingException('Transaction date is not set');
        }

        if (!isset($data['operation_type'])) {
            throw new InvalidMappingException('Transaction type is not set');
        }

        return new Transaction($user, $money, new \DateTime($data['operation_date']), $data['operation_type']);
    }
}
