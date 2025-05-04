<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
use App\Systems\Registers;

class RandomDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0xc;
    }

    public function execute(Instruction $instruction): void
    {
        $result = rand(0, 0xff) & $instruction->byte2;
        $this->registers->setGeneralRegister($instruction->nibble2, $result);
    }

    public function name(): string
    {
        return 'Random Decoder';
    }
}
