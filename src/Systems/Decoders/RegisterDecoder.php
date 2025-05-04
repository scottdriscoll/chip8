<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Memory;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

class RegisterDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
        private readonly Memory $memory,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return in_array($instruction->nibble1, [0x6, 0x7, 0xa]) || ($instruction->nibble1 == 0xf && in_array($instruction->byte2, [0x55, 0x65, 0x33, 0x1e]));
    }

    public function execute(Instruction $instruction): void
    {
        switch ($instruction->nibble1) {
            case 0x6:
                $this->writeDebugOutput("Setting register $instruction->nibble2 to $instruction->byte2 ($instruction->byte2)\n");
                $this->registers->setGeneralRegister($instruction->nibble2, $instruction->byte2);
                break;
            case 0x7:
                $this->writeDebugOutput("Adding $instruction->byte2 ($instruction->byte2) to register $instruction->nibble2\n");
                $val = $this->registers->getGeneralRegister($instruction->nibble2) + $instruction->byte2;
                $this->registers->setGeneralRegister($instruction->nibble2, $val);
                $this->writeDebugOutput("Register $instruction->nibble2 now has value $val\n");
                break;
            case 0xa:
                $this->writeDebugOutput("Setting index register to $instruction->address ($instruction->address)\n");
                $this->registers->setIndexRegister($instruction->address);
                break;
            case 0xf:
                if ($instruction->byte2 === 0x55) {
                    $this->saveRegistersToMemory($instruction);
                } elseif ($instruction->byte2 === 0x65) {
                    $this->loadRegistersFromMemory($instruction);
                } elseif ($instruction->byte2 === 0x33) {
                    $this->decimalConversion($instruction);
                } elseif ($instruction->byte2 === 0x1e) {
                    $this->addToIndex($instruction);
                }
                break;
        }
    }

    private function saveRegistersToMemory(Instruction $instruction): void
    {
        $idx = $this->registers->getIndexRegister();
        for ($i = 0; $i <= $instruction->nibble2; $i++) {
            $val = $this->registers->getGeneralRegister($i);
            $this->memory->setMemoryValue($idx + $i, $val);
        }
    }

    private function loadRegistersFromMemory(Instruction $instruction): void
    {
        $idx = $this->registers->getIndexRegister();
        for ($i = 0; $i <= $instruction->nibble2; $i++) {
            $val = $this->memory->getMemoryValue($idx + $i);
            $this->registers->setGeneralRegister($i, $val);
        }
    }

    private function decimalConversion(Instruction $instruction): void
    {
        $idx = $this->registers->getIndexRegister();
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $str = str_pad((string)$vx, 3, '0', STR_PAD_LEFT);
        foreach (str_split($str) as $i => $char) {
            $this->memory->setMemoryValue($idx + $i, hexdec($char));
        }
    }

    private function addToIndex(Instruction $instruction): void
    {
        $idx = $this->registers->getIndexRegister();
        $this->registers->setIndexRegister($idx + $instruction->byte2);
    }

    public function name(): string
    {
        return 'Register Decoder';
    }
}