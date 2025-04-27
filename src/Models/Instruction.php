<?php

namespace App\Models;

use Symfony\Component\DependencyInjection\Attribute\Exclude;

#[Exclude]
class Instruction
{
    public string $byte1;
    public int $byte1Int;
    public string $byte2;
    public int $byte2Int;
    public string $nibble1;
    public int $nibble1Int;
    public string $nibble2;
    public int $nibble2Int;
    public string $nibble3;
    public int $nibble3Int;
    public string $nibble4;
    public int $nibble4Int;
    public string $address;
    public int $addressInt;

    public static function fromBytes(string $b1, string $b2): self
    {
        $instruction = new self();
        $instruction->byte1 = $b1;
        $instruction->byte1Int = hexdec($b1);
        $instruction->byte2 = $b2;
        $instruction->byte2Int = hexdec($b2);
        $instruction->nibble1 = substr($b1, 0, 1);
        $instruction->nibble1Int = hexdec($instruction->nibble1);
        $instruction->nibble2 = substr($b1, 1, 1);
        $instruction->nibble2Int = hexdec($instruction->nibble2);
        $instruction->nibble3 = substr($b2, 0, 1);
        $instruction->nibble3Int = hexdec($instruction->nibble3);
        $instruction->nibble4 = substr($b2, 1, 1);
        $instruction->nibble4Int = hexdec($instruction->nibble4);
        $instruction->address = $instruction->nibble2 . substr($b2, 0, 2);
        $instruction->addressInt = hexdec($instruction->address);

        return $instruction;
    }

    public function __toString(): string
    {
        return $this->byte1 . $this->byte2;
    }
}
