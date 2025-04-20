<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;

class DrawDecoder implements DecoderInterface
{
    public function __construct(
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 'd';
    }

    public function execute(Instruction $instruction): void
    {

    }

    public function name(): string
    {
        return 'Draw Decoder';
    }
}