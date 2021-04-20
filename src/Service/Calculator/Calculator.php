<?php

namespace App\Service\Calculator;

use App\Entity\Money;
use App\Repository\TransactionRepository;
use App\Service\Rule\CommissionRule;
use App\Service\Rule\DiscountRule;
use App\Service\Converter\CurrencyConverterInterface;
use App\Service\Rule\Provider\CommissionRuleProviderInterface;
use App\Service\Rule\Provider\DiscountRuleProviderInterface;
use App\Entity\Transaction;

class Calculator implements CalculatorInterface
{
    private DiscountRuleProviderInterface $discountRuleProvider;
    private CommissionRuleProviderInterface $commissionRuleProvider;
    private CurrencyConverterInterface $currencyConverter;
    private TransactionRepository $transactionRepository;

    public function __construct(
        DiscountRuleProviderInterface $discountRuleProvider,
        CommissionRuleProviderInterface $commissionRuleProvider,
        CurrencyConverterInterface $currencyConverter,
        TransactionRepository $transactionRepository
    ) {
        $this->discountRuleProvider = $discountRuleProvider;
        $this->commissionRuleProvider = $commissionRuleProvider;
        $this->currencyConverter = $currencyConverter;
        $this->transactionRepository = $transactionRepository;
    }

    public function calculate(Transaction $transaction): Money
    {
        $discountRule = $this->discountRuleProvider->invoke($transaction);
        $sum = $transaction->getMoney();

        if ($discountRule !== null) {
            $sum = $this->getDiscount($transaction, $discountRule);
        }

	$this->transactionRepository->add($transaction);
        $commissionRule = $this->commissionRuleProvider->invoke($transaction);
        $commission = $this->getCommission($sum, $commissionRule);

        return $commission->ceil();
    }

    private function getCommission(Money $money, CommissionRule $commissionRule): Money
    {
	return $money->multiply($commissionRule->getPercent() / 100);
    }

    private function getDiscount(Transaction $transaction, DiscountRule $discountRule): Money
    {
        $weekSumLimit = $discountRule->getWeekMaxAmount();
        if ($weekSumLimit === null) {
            return $transaction->getMoney();
        }

        $weekTransactions = $this->transactionRepository->findByPeriod($transaction);
        $weekCountLimit = $discountRule->getWeekMaxOperations();
        if ($weekCountLimit !== null && count($weekTransactions) >= $weekCountLimit) {
            return $transaction->getMoney();
        }

        $currency = $transaction->getMoney()->getCurrency();
        $weekSum = Money::factory(0, $currency);
        foreach ($weekTransactions as $weekTransaction) {
            $weekSum = $weekSum->add($this->currencyConverter->convert($weekTransaction->getMoney(), $currency));
        }

        $weekSumLimitConverted = $this->currencyConverter->convert($weekSumLimit, $currency);
        if ($weekSum->isGreater($weekSumLimitConverted)) {
            return $transaction->getMoney();
        }

        $discount = $weekSum->add($transaction->getMoney())->subtract($weekSumLimitConverted);
        if ($discount->isNegative()) {
            return Money::factory(0, $currency);
        }

        return $discount;
    }
}
