<?php

namespace App\Service\Rule\Provider;

use App\Service\Rule\CommissionRule;
use App\Entity\Transaction;
use App\Exception\MissedCommissionRuleException;
use App\Service\Rule\Factory\CommissionRuleFactory;

class CommissionRuleProvider implements CommissionRuleProviderInterface
{
    private CommissionRuleFactory $commissionRuleFactory;
    private array $commissionRuleConfig;

    public function __construct(
        CommissionRuleFactory $commissionRuleFactory,
	array $commissionRuleConfig = []
    ) {
	$this->commissionRuleFactory = $commissionRuleFactory;
	$this->commissionRuleConfig = $commissionRuleConfig;
    }

    /**
     * @param Transaction $transaction
     * @return CommissionRule
     * @throws MissedCommissionRuleException
     */
    public function invoke(Transaction $transaction): CommissionRule
    {
	foreach ($this->commissionRuleConfig as $ruleConfig) {
	    $rule = $this->commissionRuleFactory->createFromConfig($ruleConfig);

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

	throw new MissedCommissionRuleException(
	    sprintf('Cannot find %s-%s commission rule', $transaction->getType(), $transaction->getUser()->getType())
	);
    }
}
