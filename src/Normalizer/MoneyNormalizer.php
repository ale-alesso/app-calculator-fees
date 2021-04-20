<?php

namespace App\Normalizer;

use App\Entity\Money;
use App\Exception\InvalidMappingException;

class MoneyNormalizer
{
    /**
     * @param array $data
     * @return Money
     * @throws InvalidMappingException
     */
    public function toObject(array $data): Money
    {
        if (!isset($data['amount'])) {
            throw new InvalidMappingException('Amount is not set');
        }

        if (!isset($data['currency'])) {
            throw new InvalidMappingException('Currency is not set');
        }

        return new Money($data['amount'], $data['currency']);
    }
}
