<?php

namespace App\Systems;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
class ScreenUnit
{
    public function __construct(
        private ?string $currentValue = null,
        private ?string $nextValue = ' ',
    ) {
    }

    public function hasChanged(): bool
    {
        return $this->currentValue != $this->nextValue;
    }

    public function nextFrame(): void
    {
        $this->currentValue = $this->nextValue;
    }

    public function setNext(string $value): void
    {
        $this->nextValue = $value;
    }

    public function getNext(): string
    {
        return $this->nextValue;
    }

    public function getCurrent(): ?string
    {
        return $this->currentValue;
    }
}
