<?php

namespace App\Systems;

class ProgramCounter
{
    public function __construct(
        private int $pc = Memory::ROM_START,
    ) {
    }

    public function increment(): void
    {
        $this->pc += 2;
    }

    public function get(): int
    {
        return $this->pc;
    }
}
