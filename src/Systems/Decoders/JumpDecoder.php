<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;

class JumpDecoder implements DecoderInterface
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

    }

    public function name(): string
    {
        return 'Jump Decoder';
    }
}