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
        $this->registers->setGeneralRegister($instruction->nibble2Int, $this->registers->getGeneralRegister($instruction->nibble3Int ?? ''));
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
            $this->registers->setGeneralRegister(0xF, '1');
        } else {
            $this->registers->setGeneralRegister(0xF, '0');
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val&0xff));
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
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($newVal&0xff));
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
        }
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($newVal&0xff));
    }

    private function shift(Instruction $instruction, bool $left): void
    {
        $spam = 1;
        if ($spam) echo "\nShift $instruction->nibble4 $left from $instruction->nibble2\n";
        if ($spam) echo "$instruction->byte1$instruction->byte2\n";
        $vy = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
        if ($spam) echo "vy: $vy \n";
        $val = $left ? ($vy << 1) : ($vy >> 1);
        $val &= 0xff;
        if ($spam) echo "val: $val \n";

        if ($spam) echo "final: " . dechex($val) . " \n";
        $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($val));

        if ($left) {
            $this->registers->setGeneralRegister(0xF, (($vy >> 7) & 0x1) ? '1' : '0');
        } else {
            $this->registers->setGeneralRegister(0xF, ($vy & 0x1) ? '1' : '0');
        }
    }
}
