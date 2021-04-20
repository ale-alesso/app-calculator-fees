<?php

namespace App\Entity;

use DateTimeInterface;

class Transaction
{
    const TRANSACTION_DEPOSIT = 'deposit';
    const TRANSACTION_WITHDRAW = 'withdraw';

    public static array $types = [
	self::TRANSACTION_DEPOSIT,
	self::TRANSACTION_WITHDRAW,
    ];

    private DateTimeInterface $createdAt;
    private User $user;
    private Money $money;
    private string $type;

    public function __construct(User $user, Money $money, DateTimeInterface $createdAt, string $type)
    {
        $this->createdAt = $createdAt;
        $this->user = $user;
        $this->money = $money;
        $this->type = $type;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getMoney(): Money
    {
	return $this->money;
    }

    public function setMoney(Money $money): self
    {
	$this->money = $money;

	return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isDeposit(): bool
    {
        return $this->type === self::TRANSACTION_DEPOSIT;
    }

    public function isWithdrawal(): bool
    {
        return $this->type === self::TRANSACTION_WITHDRAW;
    }
}
