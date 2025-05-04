<?php

namespace App\Systems;

class Stack
{
    /**
     * @var array<int, int>
     */
    private array $stack = [];

    public function push(int $value): void
    {
        $this->stack[] = $value;
    }

    public function pop(): int
    {
        return array_pop($this->stack) ?? 0;
    }
}
