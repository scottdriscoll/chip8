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
        return in_array($instruction->nibble1, ['6', '7', 'a']) || ($instruction->nibble1 == 'f' && in_array($instruction->byte2, ['55', '65', '33']));
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
                $val = (int) (hexdec($this->registers->getGeneralRegister($instruction->nibble2Int)) + $instruction->byte2Int);
                if ($val >= 255) {
                    $val -= 256;
                }
                $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val));
                $this->writeDebugOutput("Register $instruction->nibble2 now has value $val\n");
                break;
            case 'a':
                $this->writeDebugOutput("Setting index register to $instruction->address ($instruction->addressInt)\n");
                $this->registers->setIndexRegister($instruction->address);
                break;
            case 'f':
                if ($instruction->byte2 === '55') {
                    $this->saveRegistersToMemory($instruction);
                } elseif ($instruction->byte2 === '65') {
                    $this->loadRegistersFromMemory($instruction);
                } elseif ($instruction->byte2 === '33') {
                    $this->decimalConversion($instruction);
                }
                break;
        }
    }

    private function saveRegistersToMemory(Instruction $instruction): void
    {
        $idx = hexdec($this->registers->getIndexRegister());
        for ($i = 0; $i <= $instruction->nibble2Int; $i++) {
            $val = $this->registers->getGeneralRegister($i);
            $this->memory->setMemoryValue($idx + $i, $val ?? '');
        }
    }

    private function loadRegistersFromMemory(Instruction $instruction): void
    {
        $idx = hexdec($this->registers->getIndexRegister());
        for ($i = 0; $i <= $instruction->nibble2Int; $i++) {
            $val = (string) $this->memory->getMemoryValue($idx + $i);
            $this->registers->setGeneralRegister($i, $val);
        }
    }

    private function decimalConversion(Instruction $instruction): void
    {
        $idx = hexdec($this->registers->getIndexRegister());
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $str = str_pad($vx, 3, '0', STR_PAD_LEFT);
        foreach (str_split($str) as $i => $char) {
            $this->memory->setMemoryValue($idx + $i, $char);
        }
    }

    public function name(): string
    {
        return 'Register Decoder';
    }
}