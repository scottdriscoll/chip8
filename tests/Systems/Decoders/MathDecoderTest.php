<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\MathDecoder;
use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class MathDecoderTest extends TestCase
{
    private Registers $registers;
    private MathDecoder $mathDecoder;

    protected function setUp(): void
    {
        $this->registers = new Registers();
        $this->mathDecoder = new MathDecoder($this->registers);
    }

    public function testShiftLeftUsesVxAsSource(): void
    {
        $this->registers->setGeneralRegister(0x5, 0x03);
        $this->registers->setGeneralRegister(0x0, 0x18);

        $this->mathDecoder->execute(Instruction::fromBytes(0x85, 0x0e));

        $this->assertSame(0x06, $this->registers->getGeneralRegister(0x5));
        $this->assertSame(0, $this->registers->getFlagRegister());
    }

    public function testShiftRightUsesVxAsSource(): void
    {
        $this->registers->setGeneralRegister(0x5, 0x03);
        $this->registers->setGeneralRegister(0x0, 0x18);

        $this->mathDecoder->execute(Instruction::fromBytes(0x85, 0x06));

        $this->assertSame(0x01, $this->registers->getGeneralRegister(0x5));
        $this->assertSame(1, $this->registers->getFlagRegister());
    }
}
