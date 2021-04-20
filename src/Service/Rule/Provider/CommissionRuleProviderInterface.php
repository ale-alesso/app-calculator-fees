<?php

namespace App\Service\Rule\Provider;

use App\Service\Rule\CommissionRule;
use App\Entity\Transaction;

interface CommissionRuleProviderInterface
{
    public function invoke(Transaction $transaction): CommissionRule;
}
