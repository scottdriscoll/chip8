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
        return in_array($instruction->nibble1, ['1', 'b']);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === '1') {
            $this->writeDebugOutput("Jumping to {$instruction->address}\n");
            $this->programCounter->set($instruction->addressInt);
        } else {
            throw new \Exception('Jump to V0 not supported');

        }
    }

    public function name(): string
    {
        return 'Jump Decoder';
    }
}