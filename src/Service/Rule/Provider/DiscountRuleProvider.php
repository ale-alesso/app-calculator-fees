<?php

namespace App\Service\Rule\Provider;

use App\Service\Rule\DiscountRule;
use App\Entity\Transaction;
use App\Service\Rule\Factory\DiscountRuleFactory;

class DiscountRuleProvider implements DiscountRuleProviderInterface
{
    private DiscountRuleFactory $discountRuleFactory;
    private array $discountRuleConfig;

    public function __construct(
	DiscountRuleFactory $discountRuleFactory,
	array $discountRuleConfig = []
    ) {
	$this->discountRuleFactory = $discountRuleFactory;
	$this->discountRuleConfig = $discountRuleConfig;
    }

    public function invoke(Transaction $transaction): ?DiscountRule
    {
	foreach ($this->discountRuleConfig as $ruleConfig) {
	    $rule = $this->discountRuleFactory->createFromConfig($ruleConfig);

	    if ($rule->getTransactionType() !== null
		&& $rule->getTransactionType() !== $transaction->getType()) {
		continue;
	    }

	    if ($rule->getUserType() !== null
		&& $rule->getUserType() !== $transaction->getUser()->getType()) {
		continue;
	    }

	    return $rule;
	}

	return null;
    }
}
