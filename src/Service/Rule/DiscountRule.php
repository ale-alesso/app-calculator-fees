<?php

namespace App\Service\Rule;

use App\Entity\Money;

class DiscountRule
{
    private ?string $transactionType;
    private ?string $userType;
    private ?int $weekMaxOperations;
    private ?string $weekMaxAmount;
    private ?string $weekOperationsCurrency;

    public function __construct($transactionType = null, $userType = null, $weekMaxOperations = null, $weekMaxAmount = null, $weekOperationsCurrency = null)
    {
        $this->transactionType = $transactionType;
        $this->userType = $userType;
        $this->weekMaxOperations = $weekMaxOperations;
        $this->weekMaxAmount = $weekMaxAmount;
        $this->weekOperationsCurrency = $weekOperationsCurrency;
    }

    public function setOperation(?string $operation): self
    {
        $this->transactionType = $operation;

        return $this;
    }

    public function getTransactionType(): ?string
    {
        return $this->transactionType;
    }

    public function setUserType(?string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setWeekMaxOperations(?int $weekMaxOperations): self
    {
        $this->weekMaxOperations = $weekMaxOperations;

        return $this;
    }

    public function getWeekMaxOperations(): ?int
    {
        return $this->weekMaxOperations;
    }

    public function setWeekSum(?Money $weekSumAmount): self
    {
	$this->weekMaxAmount = ($weekSumAmount) ? $weekSumAmount->getAmount() : null;
	$this->weekOperationsCurrency = ($weekSumAmount) ? $weekSumAmount->getCurrency() : null;

        return $this;
    }

    public function getWeekMaxAmount(): ?Money
    {
        if ($this->weekMaxAmount !== null
	    && $this->weekOperationsCurrency !== null) {
            return Money::factory($this->weekMaxAmount, $this->weekOperationsCurrency);
        }

        return null;
    }
}
