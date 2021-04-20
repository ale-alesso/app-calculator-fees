<?php

namespace App\Service\Rule\Factory;

use App\Service\Rule\CommissionRule;

class CommissionRuleFactory
{
    public function createFromConfig(array $config): CommissionRule
    {
        return new CommissionRule(...$config);
    }
}
