<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Memory;
use App\Systems\Registers;

class FontDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
        private readonly Memory $memory,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0xf && $instruction->byte2 == 0x29;
    }

    public function execute(Instruction $instruction): void
    {
        $char = $this->registers->getGeneralRegister($instruction->nibble2);
        $idx = $this->memory->getFontIndex($char);
        $this->registers->setIndexRegister($idx);
    }

    public function name(): string
    {
        return 'Font Decoder';
    }
}
