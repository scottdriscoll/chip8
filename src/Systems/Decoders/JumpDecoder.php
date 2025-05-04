<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

class JumpDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly ProgramCounter $programCounter,
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return in_array($instruction->nibble1, [0x1, 0xb]);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === 0x1) {
            $this->writeDebugOutput("Jumping to {$instruction->address}\n");
            $this->programCounter->set($instruction->address);
        } else {
            // Jump with offset
            $v0 = $this->registers->getGeneralRegister(0x0);
            $this->programCounter->set($instruction->address + $v0);
        }
    }

    public function name(): string
    {
        return 'Jump Decoder';
    }
}