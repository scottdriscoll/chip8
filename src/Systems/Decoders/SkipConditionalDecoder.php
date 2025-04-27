<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
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
        return in_array($instruction->nibble1, ['3', '4', '5', '9']);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === '3') {
            $this->skipIfEqual($instruction);
        } elseif ($instruction->nibble1 === '4') {
            $this->skipIfNotEqual($instruction);
        } elseif ($instruction->nibble1 === '5') {
            $this->skipIfXEqualY($instruction);
        } elseif ($instruction->nibble1 === '9') {
            $this->skipIfXNotEqualY($instruction);
        }
    }

    public function name(): string
    {
        return 'Skip Conditional Decoder';
    }

    private function skipIfEqual(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2Int) === $instruction->byte2) {
            $this->programCounter->increment();
        }
    }

    private function skipIfNotEqual(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2Int) !== $instruction->byte2) {
            $this->programCounter->increment();
        }
    }

    private function skipIfXEqualY(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2Int) === $this->registers->getGeneralRegister($instruction->nibble3Int)) {
            $this->programCounter->increment();
        }
    }

    private function skipIfXNotEqualY(Instruction $instruction): void
    {
        if ($this->registers->getGeneralRegister($instruction->nibble2Int) !== $this->registers->getGeneralRegister($instruction->nibble3Int)) {
            $this->programCounter->increment();
        }
    }
}
