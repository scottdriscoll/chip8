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

    public function decrement(): void
    {
        $this->pc -= 2;
    }

    public function get(): int
    {
        return $this->pc;
    }

    public function set(int $pc): void
    {
        $this->pc = $pc & 0xfff;
    }
}
