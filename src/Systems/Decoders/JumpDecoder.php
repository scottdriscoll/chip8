<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;

class JumpDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly ProgramCounter $programCounter,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === '1';
    }

    public function execute(Instruction $instruction): void
    {
        $this->writeDebugOutput("Jumping to {$instruction->address}\n");
        $this->programCounter->set($instruction->addressInt);
    }

    public function name(): string
    {
        return 'Jump Decoder';
    }
}