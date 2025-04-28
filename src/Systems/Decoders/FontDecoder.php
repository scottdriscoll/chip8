<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
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
        return $instruction->nibble1 === 'f' && $instruction->byte2 == '29';
    }

    public function execute(Instruction $instruction): void
    {
        $char = dechex(hexdec($this->registers->getGeneralRegister($instruction->nibble2Int)));
        $idx = $this->memory->getFontIndex($char);
        $this->registers->setIndexRegister(hexdec($idx));
    }

    public function name(): string
    {
        return 'Font Decoder';
    }
}
