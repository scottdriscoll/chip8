<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\RegisterDecoder;
use App\Systems\Memory;
use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class RegisterDecoderTest extends TestCase
{
    private Registers $registers;
    private Memory $memory;
    private RegisterDecoder $registerDecoder;

    protected function setUp(): void
    {
        $this->registers = new Registers();
        $this->memory = new Memory();
        $this->registerDecoder = new RegisterDecoder($this->registers, $this->memory);
    }

    public function testSetRegister(): void
    {
        $instruction = Instruction::fromBytes(0x61, 0xe0);
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertSame(0, $this->registers->getGeneralRegister(0));
        $this->assertSame(0xe0, $this->registers->getGeneralRegister(1));
        for ($i = 2; $i < 16; $i++) {
            $this->assertSame(0, $this->registers->getGeneralRegister($i));
        }
    }

    public function testAddRegister(): void
    {
        $instruction = Instruction::fromBytes(0x61, 0xe0);
        $this->registerDecoder->execute($instruction);
        $instruction = Instruction::fromBytes(0x71, 0x03);
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertSame(0xe3, $this->registers->getGeneralRegister(1));
    }

    public function testSetIndexRegister(): void
    {
        $instruction = Instruction::fromBytes(0xa1, 0xe0);
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertSame(0x1e0, $this->registers->getIndexRegister());
    }
}
