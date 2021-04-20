<?php

namespace App\Service\Rule\Provider;

use App\Service\Rule\DiscountRule;
use App\Entity\Transaction;

interface DiscountRuleProviderInterface
{
    public function invoke(Transaction $transaction): ?DiscountRule;
}
