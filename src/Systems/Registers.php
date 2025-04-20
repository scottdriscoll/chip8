<?php

namespace App\Systems;

class Registers
{
    const REGISTER_SIZE = 16;

    /**
     * @var array<int<0,15>, string|null>
     */
    private array $generalRegisters = [];

    private ?string $indexRegister = null;

    public function __construct()
    {
        $this->generalRegisters = array_fill(0, self::REGISTER_SIZE, null);
    }

    public function setGeneralRegister(int $index, string $value): void
    {
        if ($index < 0 || $index >= self::REGISTER_SIZE) {
            throw new \InvalidArgumentException('General register index must be between 0 and 15.');
        }

        $this->generalRegisters[$index] = $value;
    }

    public function getGeneralRegister(int $index): ?string
    {
        if ($index < 0 || $index >= self::REGISTER_SIZE) {
            throw new \InvalidArgumentException('General register index must be between 0 and 15.');
        }

        return $this->generalRegisters[$index];
    }

    public function setIndexRegister(string $value): void
    {
        $this->indexRegister = $value;
    }

    public function getIndexRegister(): ?string
    {
        return $this->indexRegister;
    }
}
