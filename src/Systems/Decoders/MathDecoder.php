<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
use App\Systems\Registers;

class MathDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === '8';
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble4 === '0') {
            $this->set($instruction);
        } elseif ($instruction->nibble4 === '1') {
            $this->vxORvy($instruction);
        } elseif ($instruction->nibble4 === '2') {
            $this->vxANDvy($instruction);
        } elseif ($instruction->nibble4 === '3') {
            $this->vxXORvy($instruction);
        } elseif ($instruction->nibble4 === '4') {
            $this->vxAddvy($instruction);
        } elseif ($instruction->nibble4 === '5') {
            $this->vxSubvy($instruction);
        } elseif ($instruction->nibble4 === '7') {
            $this->vxSubnvy($instruction);
        } elseif ($instruction->nibble4 === '6' || $instruction->nibble4 === 'e') {
            $this->shift($instruction, $instruction->nibble4 === 'e');
        } else {
            throw new \Exception("Unknown instruction in ShiftDecoder: $instruction->byte1$instruction->byte2");
        }
    }

    public function name(): string
    {
        return 'Math Decoder';
    }

    private function set(Instruction $instruction): void
    {
        $this->registers->setGeneralRegister($instruction->nibble2Int, $this->registers->getGeneralRegister($instruction->nibble3Int));
    }

    private function vxORvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));

        $val = dechex($vx | $vy);
        $this->registers->setGeneralRegister($instruction->nibble2Int, $val);
    }

    private function vxANDvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));

        $val = dechex($vx & $vy);
        $this->registers->setGeneralRegister($instruction->nibble2Int, $val);
    }

    private function vxXORvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));

        $val = dechex($vx ^ $vy);
        $this->registers->setGeneralRegister($instruction->nibble2Int, $val);
    }

    private function vxAddvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));

        $val = $vx + $vy;
        if ($val > 0xFF) {
            $val -= 0x100;
            $this->registers->setGeneralRegister(0xF, '1');
        } else {
            $this->registers->setGeneralRegister(0xF, '0');
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val));
    }

    private function vxSubvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));
        $newVal = $vx - $vy;
        if ($vx > $vy) {
            $this->registers->setGeneralRegister(0xF, '1');
        } elseif ($vy > $vx) {
            $this->registers->setGeneralRegister(0xF, '0');
            if ($newVal < 0) {
                $newVal += 0x100;
            }
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($newVal));
    }

    private function vxSubnvy(Instruction $instruction): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble3Int));
        $newVal = $vy - $vx;
        if ($vy > $vx) {
            $this->registers->setGeneralRegister(0xF, '1');
        } elseif ($vx > $vy) {
            $this->registers->setGeneralRegister(0xF, '0');
            if ($newVal < 0) {
                $newVal += 0x100;
            }
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($newVal));
    }

    private function shift(Instruction $instruction, bool $left): void
    {
        $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        $this->writeDebugOutput("Shifting $vx $instruction->nibble4 $left\n");
        $this->writeDebugOutput("vx: $vx\n");
        $val = $left ? $vx << 1 : $vx >> 1;
        if ($left) {
            $this->registers->setGeneralRegister(0xF, ($vx & 0x80) ? '1' : '0');
            $this->writeDebugOutput("vx & 0x80: " . ($vx & 0x80) . "\n");
        } else {
            $this->registers->setGeneralRegister(0xF, ($vx & 0x1) ? '1' : '0');
            $this->writeDebugOutput("vx & 0x1: " . ($vx & 0x1) . "\n");
        }
        if ($val > 0xFF) {
            $val -= 0x100;
        }
        $this->writeDebugOutput("newVal: $val\n");
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val));
    }
}
