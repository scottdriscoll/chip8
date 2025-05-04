<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;

class KeyboardDecoder extends AbstractDecoder implements DecoderInterface
{

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0xe || ($instruction->nibble1 === 0xf && $instruction->byte2 === 0x0a);
    }

    public function execute(Instruction $instruction): void
    {

    }

    public function name(): string
    {
        return 'Keyboard Decoder';
    }
}
