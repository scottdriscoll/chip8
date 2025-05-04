<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;

class KeyboardDecoder extends AbstractDecoder implements DecoderInterface
{

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0xe;
    }

    public function execute(Instruction $instruction): void
    {
        throw new \Exception('Not implemented');
    }

    public function name(): string
    {
        return 'Keyboard Decoder';
    }
}
