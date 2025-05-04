<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Registers;

class MathDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0x8;
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble4 === 0) {
            $this->set($instruction);
        } elseif ($instruction->nibble4 === 0x1) {
            $this->vxORvy($instruction);
        } elseif ($instruction->nibble4 === 0x2) {
            $this->vxANDvy($instruction);
        } elseif ($instruction->nibble4 === 0x3) {
            $this->vxXORvy($instruction);
        } elseif ($instruction->nibble4 === 0x4) {
            $this->vxAddvy($instruction);
        } elseif ($instruction->nibble4 === 0x5) {
            $this->vxSubvy($instruction);
        } elseif ($instruction->nibble4 === 0x7) {
            $this->vxSubnvy($instruction);
        } elseif ($instruction->nibble4 === 0x6 || $instruction->nibble4 === 0xe) {
            $this->shift($instruction, $instruction->nibble4 === 0xe);
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
        $this->registers->setGeneralRegister($instruction->nibble2, $this->registers->getGeneralRegister($instruction->nibble3));
    }

    private function vxORvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);

        $val = $vx | $vy;
        $this->registers->setGeneralRegister($instruction->nibble2, $val);
    }

    private function vxANDvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);

        $val = $vx & $vy;
        $this->registers->setGeneralRegister($instruction->nibble2, $val);
    }

    private function vxXORvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);

        $val = $vx ^ $vy;
        $this->registers->setGeneralRegister($instruction->nibble2, $val);
    }

    private function vxAddvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);

        $val = $vx + $vy;
        if ($val > 0xFF) {
            $this->registers->setGeneralRegister(0xF, 0x1);
        } else {
            $this->registers->setGeneralRegister(0xF, 0);
        }
        $this->registers->setGeneralRegister($instruction->nibble2, $val);
    }

    private function vxSubvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);
        $newVal = $vx - $vy;
        if ($vx > $vy) {
            $this->registers->setGeneralRegister(0xF, 0x1);
        } elseif ($vy > $vx) {
            $this->registers->setGeneralRegister(0xF, 0);
        }
        $this->registers->setGeneralRegister($instruction->nibble2, $newVal);
    }

    private function vxSubnvy(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);
        $vy = $this->registers->getGeneralRegister($instruction->nibble3);
        $newVal = $vy - $vx;
        if ($vy > $vx) {
            $this->registers->setGeneralRegister(0xF, 0x1);
        } elseif ($vx > $vy) {
            $this->registers->setGeneralRegister(0xF, 0);
        }
        $this->registers->setGeneralRegister($instruction->nibble2, $newVal);
    }

    private function shift(Instruction $instruction, bool $left): void
    {
        $vy = $this->registers->getGeneralRegister($instruction->nibble2);
        $val = $left ? ($vy << 1) : ($vy >> 1);
        $this->registers->setGeneralRegister($instruction->nibble2, $val);

        if ($left) {
            $this->registers->setGeneralRegister(0xF, (($vy >> 7) & 0x1) ? 0x1 : 0);
        } else {
            $this->registers->setGeneralRegister(0xF, ($vy & 0x1) ? 0x1 : 0);
        }
    }
}
