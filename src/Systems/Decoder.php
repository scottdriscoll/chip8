<?php

namespace App\Systems;

use App\Models\Instruction;
use App\Systems\Decoders\DecoderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class Decoder
{
    public function __construct(
        #[AutowireIterator(DecoderInterface::class)] private iterable $decoders,
    ) {
    }

    public function decodeInstruction(Instruction $instruction): DecoderInterface
    {
        foreach ($this->decoders as $decoder) {
            if ($decoder->supports($instruction)) {
                return $decoder;
            }
        }

        throw new \Exception('Instruction not supported: ' . $instruction->byte1 . $instruction->byte2);
    }
}
