<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

class RegisterDecoder extends AbstractDecoder implements DecoderInterface
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
                $this->writeDebugOutput("Setting register $instruction->nibble2 to $instruction->byte2 ($instruction->byte2Int)\n");
                $this->registers->setGeneralRegister($instruction->nibble2Int, $instruction->byte2);
                break;
            case '7':
                $this->writeDebugOutput("Adding $instruction->byte2 ($instruction->byte2Int) to register $instruction->nibble2\n");
                $val = (int) hexdec($this->registers->getGeneralRegister($instruction->nibble2Int)) + $instruction->byte2Int;
                $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val));
                $this->writeDebugOutput("Register $instruction->nibble2 now has value $val\n");
                break;
            case 'a':
                $this->writeDebugOutput("Setting index register to $instruction->address ($instruction->addressInt)\n");
                $this->registers->setIndexRegister($instruction->address);
                break;
        }
    }

    public function name(): string
    {
        return 'Register Decoder';
    }
}