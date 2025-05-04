<?php

namespace App\Systems;

class Registers
{
    const REGISTER_SIZE = 16;

    /**
     * @var array<int<0,15>, int>
     */
    private array $generalRegisters;

    private int $indexRegister = 0;

    public function __construct()
    {
        $this->generalRegisters = array_fill(0, self::REGISTER_SIZE, 0);
    }

    public function setGeneralRegister(int $index, int $value): void
    {
        if ($index < 0 || $index >= self::REGISTER_SIZE) {
            throw new \InvalidArgumentException('General register index must be between 0 and 15.');
        }

        $this->generalRegisters[$index] = $value & 0xff;
    }

    public function getGeneralRegister(int $index): int
    {
        if ($index < 0 || $index >= self::REGISTER_SIZE) {
            throw new \InvalidArgumentException('General register index must be between 0 and 15.');
        }

        return $this->generalRegisters[$index];
    }

    public function setIndexRegister(int $value): void
    {
        $this->indexRegister = $value & 0xfff;
    }

    public function getIndexRegister(): int
    {
        return $this->indexRegister ?? 0;
    }

    public function setFlagRegister(int $value): void
    {
        $this->setGeneralRegister(0xf, $value);
    }

    public function getFlagRegister(): int
    {
        return $this->getGeneralRegister(0xf);
    }
}
