<?php

namespace App\Entity;

class User
{
    const USER_TYPE_PRIVATE = 'private';
    const USER_TYPE_BUSINESS = 'business';

    public static array $types = [
	self::USER_TYPE_PRIVATE,
	self::USER_TYPE_BUSINESS,
    ];

    private ?int $id;
    private ?string $type;

    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isPrivate(): bool
    {
        return $this->type === self::USER_TYPE_PRIVATE;
    }

    public function isBusiness(): bool
    {
        return $this->type === self::USER_TYPE_BUSINESS;
    }
}
