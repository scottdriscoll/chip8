<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\DrawDecoder;
use App\Systems\Display;
use App\Systems\Memory;
use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class DrawDecoderTest extends TestCase
{
    private Display $display;
    private Registers $registers;
    private Memory $memory;
    private DrawDecoder $drawDecoder;

    protected function setUp(): void
    {
        $this->display = new Display();
        $this->display->initialize();
        $this->registers = new Registers();
        $this->memory = new Memory();
        $this->drawDecoder = new DrawDecoder($this->display, $this->registers, $this->memory);
    }

    public function testDrawSetsCollisionOnlyWhenPixelIsErased(): void
    {
        $this->registers->setIndexRegister(0x300);
        $this->memory->setMemoryValue(0x300, 0x80);

        $instruction = Instruction::fromBytes(0xd0, 0x11);
        $this->drawDecoder->execute($instruction);

        $this->assertTrue($this->display->pixelEnabled(0, 0));
        $this->assertSame(0, $this->registers->getFlagRegister());

        $this->drawDecoder->execute($instruction);

        $this->assertFalse($this->display->pixelEnabled(0, 0));
        $this->assertSame(1, $this->registers->getFlagRegister());
    }
}
