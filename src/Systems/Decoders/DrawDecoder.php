<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Display;
use App\Systems\Memory;
use App\Systems\Registers;

class DrawDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * @var array<int, int> $bits
     */
    private array $bits = [0x80, 0x40, 0x20, 0x10, 0x08, 0x04, 0x02, 0x01];

    public function __construct(
        private readonly Display $display,
        private readonly Registers $registers,
        private readonly Memory $memory,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        if ($instruction->byte1 === 0 && $instruction->byte2 === 0xfe) {
            throw new \Exception('Lowres mode not implemented');
        }
        if ($instruction->byte1 === 0 && $instruction->byte2 === 0xff) {
            throw new \Exception('Hires mode not implemented');
        }

        return $instruction->nibble1 === 0xd;
    }

    public function execute(Instruction $instruction): void
    {
        $vx = $instruction->nibble2;
        $vy = $instruction->nibble3;
        $lines = $instruction->nibble4;
        $address = $this->registers->getIndexRegister();
        $pixelsToggled = false;

        // Turn off VF
        $this->registers->setFlagRegister(0);

        for ($line = 0; $line < $lines; $line++) {
            $sprite = $this->memory->getMemoryValue($address + $line);
            $x = $this->registers->getGeneralRegister($vx) & (Display::WIDTH - 1);
            $y = $this->registers->getGeneralRegister($vy) & (Display::HEIGHT - 1);
            $this->writeDebugOutput("Drawing sprite $sprite at $x, $y\n");

            for ($i = 0; $i < 8; $i++) {
                $bitEnabled = (bool) ($sprite & $this->bits[$i]);
                $oldEnabled = $this->display->pixelEnabled($x + $i, $y + $line);
                $newEnabled = ($bitEnabled xor $oldEnabled);
                if ($newEnabled && !$oldEnabled) {
                    $pixelsToggled = true;
                }

                $this->display->setEnabled($x + $i, $y + $line, $newEnabled);
                $this->writeDebugOutput("Pixel $x + $i, $y + $line is now $newEnabled (was $oldEnabled, source $bitEnabled)\n");
            }
        }

        if ($pixelsToggled) {
            $this->registers->setFlagRegister(0x1);
        }

        $this->display->draw();
    }

    public function name(): string
    {
        return 'Draw Decoder';
    }
}