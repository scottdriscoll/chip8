<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

class RegisterDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return in_array($instruction->nibble1, ['6', '7', 'a']);
    }

    public function execute(Instruction $instruction): void
    {

    }

    public function name(): string
    {
        return 'Register Decoder';
    }
}