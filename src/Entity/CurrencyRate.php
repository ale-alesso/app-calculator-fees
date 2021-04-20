<?php

namespace App\Entity;

class CurrencyRate
{
    private ?string $from;
    private ?string $to;
    private ?float $value;

    public function __construct()
    {
        $this->from = null;
        $this->to = null;
        $this->value = null;
    }

    public function setFrom(?string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setTo(?string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }
}
