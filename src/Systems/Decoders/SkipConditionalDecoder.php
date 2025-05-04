<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

class SkipConditionalDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly ProgramCounter $programCounter,
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return in_array($instruction->nibble1, [0x3, 0x4, 0x5, 0x9]);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === 0x3) {
            $this->skipIfEqual($instruction);
        } elseif ($instruction->nibble1 === 0x4) {
            $this->skipIfNotEqual($instruction);
        } elseif ($instruction->nibble1 === 0x5) {
            $this->skipIfXEqualY($instruction);
        } elseif ($instruction->nibble1 === 0x9) {
            $this->skipIfXNotEqualY($instruction);
        }
    }

    public function name(): string
    {
        return 'Skip Conditional Decoder';
    }

    private function skipIfEqual(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2) === $instruction->byte2) {
            $this->programCounter->increment();
        }
    }

    private function skipIfNotEqual(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2) !== $instruction->byte2) {
            $this->programCounter->increment();
        }
    }

    private function skipIfXEqualY(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2) === $this->registers->getGeneralRegister($instruction->nibble3)) {
            $this->programCounter->increment();
        }
    }

    private function skipIfXNotEqualY(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2) !== $this->registers->getGeneralRegister($instruction->nibble3)) {
            $this->programCounter->increment();
        }
    }
}
