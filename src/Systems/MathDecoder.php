<?php

namespace App\Systems;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;

class MathDecoder extends Decoders\AbstractDecoder implements Decoders\DecoderInterface
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
}
