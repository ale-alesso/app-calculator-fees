<?php

namespace App\Service\Rule\Factory;

use App\Service\Rule\DiscountRule;

class DiscountRuleFactory
{
    public function createFromConfig(array $config): DiscountRule
    {
        return new DiscountRule(...$config);
    }
}
