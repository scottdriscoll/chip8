<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\JumpDecoder;
use App\Systems\ProgramCounter;
use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class JumpDecoderTest extends TestCase
{
    private JumpDecoder $jumpDecoder;
    private ProgramCounter $programCounter;
    private Registers $registers;

    protected function setUp(): void
    {
        $this->programCounter = new ProgramCounter();
        $this->registers = new Registers();
        $this->jumpDecoder = new JumpDecoder($this->programCounter, $this->registers);
    }

    public function testJump(): void
    {
        $instruction = Instruction::fromBytes(0x11, 0x00);
        $this->assertTrue($this->jumpDecoder->supports($instruction));
        $this->jumpDecoder->execute($instruction);
        $this->assertSame(256, $this->programCounter->get());
    }

    public function testJumpWithOffset(): void
    {
        $instruction = Instruction::fromBytes(0xb1, 0x23);
        $this->registers->setGeneralRegister(0x0, 0x10);
        $this->assertTrue($this->jumpDecoder->supports($instruction));
        $this->jumpDecoder->execute($instruction);
        $this->assertSame(0x133, $this->programCounter->get());
    }
}
