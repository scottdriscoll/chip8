<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;

class SystemDecoder extends AbstractDecoder implements DecoderInterface
{
    public function supports(Instruction $instruction): bool
    {
        if ($instruction->nibble1 !== 0x0) {
            return false;
        }

        return !in_array($instruction->byte2, [0xe0, 0xee, 0xfe, 0xff], true);
    }

    public function execute(Instruction $instruction): void
    {
        $this->writeDebugOutput("Ignoring system instruction $instruction\n");
    }

    public function name(): string
    {
        return 'System Decoder';
    }
}
