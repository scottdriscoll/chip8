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
        switch ($instruction->nibble1) {
            case '6':
                $this->registers->setGeneralRegister($instruction->nibble2, $instruction->byte2);
                break;
            case '7':
                $val = (int) hexdec($this->registers->getGeneralRegister($instruction->nibble2)) + (int) hexdec($instruction->byte2);
                $this->registers->setGeneralRegister($instruction->nibble2, dechex($val));
                break;
            case 'a':
                $this->registers->setIndexRegister($instruction->address);
                break;
        }
    }

    public function name(): string
    {
        return 'Register Decoder';
    }
}