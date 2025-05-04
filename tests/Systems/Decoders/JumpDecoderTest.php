<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\JumpDecoder;
use App\Systems\ProgramCounter;
use PHPUnit\Framework\TestCase;

class JumpDecoderTest extends TestCase
{
    private JumpDecoder $jumpDecoder;
    private ProgramCounter $programCounter;

    protected function setUp(): void
    {
        $this->programCounter = new ProgramCounter();
        $this->jumpDecoder = new JumpDecoder($this->programCounter);
    }

    public function testJump(): void
    {
        $instruction = Instruction::fromBytes(0x11, 0x00);
        $this->assertTrue($this->jumpDecoder->supports($instruction));
        $this->jumpDecoder->execute($instruction);
        $this->assertSame(256, $this->programCounter->get());
    }
}
