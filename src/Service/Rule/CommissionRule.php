<?php

namespace App\Service\Rule;

class CommissionRule
{
    private ?string $transactionType;
    private ?string $userType;
    private ?float $percent;

    public function __construct(
	$transactionType = null,
	$userType = null,
	$percent = null
    )
    {
	$this->transactionType = $transactionType;
	$this->userType = $userType;
	$this->percent = $percent;
    }

    public function setTransactionType(?string $transactionType): self
    {
	$this->transactionType = $transactionType;

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

    public function setPercent(?float $percent): self
    {
	$this->percent = $percent;

	return $this;
    }

    public function getPercent(): ?float
    {
	return $this->percent;
    }
}
