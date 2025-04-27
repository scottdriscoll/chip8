<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;

class TimerDecoder extends AbstractDecoder implements DecoderInterface
{

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 'f' && in_array($instruction->byte2, ['07',  '15', '18']);
    }

    public function execute(Instruction $instruction): void
    {
        // TODO: Implement execute() method.
    }

    public function name(): string
    {
        return 'Timer Decoder';
    }
}
