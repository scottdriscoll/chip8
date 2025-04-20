<?php

namespace App\Models;

class Instruction
{
    public string $byte1;
    public string $byte2;
    public string $nibble1;
    public string $nibble2;
    public string $nibble3;
    public string $nibble4;
    public string $address;

    public static function fromBytes(string $b1, string $b2): self
    {
        $instruction = new self();
        $instruction->byte1 = $b1;
        $instruction->byte2 = $b2;
        $instruction->nibble1 = substr($b1, 0, 1);
        $instruction->nibble2 = substr($b1, 1, 1);
        $instruction->nibble3 = substr($b2, 0, 1);
        $instruction->nibble4 = substr($b2, 1, 1);
        $instruction->address = $instruction->nibble2 . substr($b2, 0, 2);

        return $instruction;
    }
}
