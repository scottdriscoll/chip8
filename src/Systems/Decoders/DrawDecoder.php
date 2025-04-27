<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Display;
use App\Systems\Memory;
use App\Systems\Registers;

class DrawDecoder extends AbstractDecoder implements DecoderInterface
{
    private array $bits = [0x80, 0x40, 0x20, 0x10, 0x08, 0x04, 0x02, 0x01];

    public function __construct(
        private readonly Display $display,
        private readonly Registers $registers,
        private readonly Memory $memory,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 'd';
    }

    public function execute(Instruction $instruction): void
    {
        $vx = $instruction->nibble2Int;
        $vy = $instruction->nibble3Int;
        $lines = $instruction->nibble4Int;
        $address = hexdec($this->registers->getIndexRegister());
        $pixelsToggled = false;

        // Turn off VF
        $this->registers->setGeneralRegister(0xF, '0');

        for ($line = 0; $line < $lines; $line++) {
            $sprite = $this->memory->getMemoryValue($address + $line);
            $this->writeDebugOutput("Sprite: $sprite\n");
            $sprite = $sprite ? hexdec($sprite) : 0;
            $this->writeDebugOutput("SpriteInt: $sprite\n");

            $x = hexdec($this->registers->getGeneralRegister($vx)) & (Display::WIDTH - 1);
            $y = hexdec($this->registers->getGeneralRegister($vy)) & (Display::HEIGHT - 1);
            $this->writeDebugOutput("Drawing sprite $sprite at $x, $y\n");

            for ($i = 0; $i < 8; $i++) {
                $bitEnabled = (bool) ($sprite & $this->bits[$i]);
                $oldEnabled = $this->display->pixelEnabled($x + $i, $y + $i);
                $newEnabled = $bitEnabled xor $oldEnabled;
                if ($newEnabled && !$oldEnabled) {
                    $pixelsToggled = true;
                }

                $this->display->setEnabled($x + $i, $y + $line, $newEnabled);
                $this->writeDebugOutput("Pixel $x + $i, $y + $line is now $newEnabled (was $oldEnabled, source $bitEnabled)\n");
            }
        }

        if ($pixelsToggled) {
            $this->registers->setGeneralRegister(0xF, '1');
        }

        $this->display->draw();
    }

    public function name(): string
    {
        return 'Draw Decoder';
    }
}