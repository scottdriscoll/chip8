<?php

namespace App\Tests\Models;

use App\Models\Instruction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InstructionsTest extends TestCase
{
    #[DataProvider( 'provider')]
    public function testInstructions(int $byte1, int $byte2, Instruction $expected): void
    {
        $instruction = Instruction::fromBytes($byte1, $byte2);
        $this->assertEquals($expected, $instruction);

    }

    public static function provider(): \Generator
    {
        yield '00e0' => [0x00, 0xe0, new Instruction(0, 0xe0, 0, 0, 0xe, 0, 0x0e0)];
        yield '3112' => [0x31, 0x12, new Instruction(0x31, 0x12, 0x3, 0x1, 0x1, 0x2, 0x112)];
        yield '7fb6' => [0x7f, 0xb6, new Instruction(0x7f, 0xb6, 0x7, 0xf, 0xb, 0x6, 0xfb6)];
        yield 'Bfb3' => [0xBf, 0xb3, new Instruction(0xbf, 0xb3, 0xb, 0xf, 0xb, 0x3, 0xfb3)];
    }
}
