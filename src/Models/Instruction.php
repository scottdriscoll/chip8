<?php

namespace App\Models;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
class Instruction
{
    public function __construct(
        public int $byte1 = 0,
        public int $byte2 = 0,
        public int $nibble1 = 0,
        public int $nibble2 = 0,
        public int $nibble3 = 0,
        public int $nibble4 = 0,
        public int $address = 0
    ) {
    }

    public static function fromBytes(int $b1, int $b2): self
    {
        $instruction = new self();
        $instruction->byte1 = $b1;
        $instruction->byte2 = $b2;
        $instruction->nibble1 = ($b1 >> 4) & 0xF;
        $instruction->nibble2 = $b1 & 0xF;
        $instruction->nibble3 = ($b2 >> 4) & 0xF;
        $instruction->nibble4 = $b2 & 0xF;
        $instruction->address = ($instruction->nibble2 << 8) | $b2;

        // ensure proper sizes
        $instruction->byte1 = $instruction->byte1 & 0xff;
        $instruction->byte2 = $instruction->byte2 & 0xff;
        $instruction->nibble1 = $instruction->nibble1 & 0xf;
        $instruction->nibble2 = $instruction->nibble2 & 0xf;
        $instruction->nibble3 = $instruction->nibble3 & 0xf;
        $instruction->nibble4 = $instruction->nibble4 & 0xf;
        $instruction->address = $instruction->address & 0xfff;

        return $instruction;
    }

    public function __toString(): string
    {
        return $this->byte1 . $this->byte2;
    }
}
