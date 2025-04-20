<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;

class ClearScreenDecoder implements DecoderInterface
{
    public function supports(Instruction $instruction): bool
    {
        return $instruction->byte1 === '00' && $instruction->byte2 === 'e0';
    }

    public function execute(Instruction $instruction): void
    {

    }

    public function name(): string
    {
        return 'Clear Screen Decoder';
    }
}