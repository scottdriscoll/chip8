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
        $instruction = Instruction::fromBytes('61', 'e0');
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertNull($this->registers->getGeneralRegister(0));
        $this->assertSame('e0', $this->registers->getGeneralRegister(1));
        for ($i = 2; $i < 16; $i++) {
            $this->assertNull($this->registers->getGeneralRegister($i));
        }
    }

    public function testAddRegister(): void
    {
        $instruction = Instruction::fromBytes('61', 'e0');
        $this->registerDecoder->execute($instruction);
        $instruction = Instruction::fromBytes('71', '03');
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertSame('e3', $this->registers->getGeneralRegister(1));
    }

    public function testSetIndexRegister(): void
    {
        $instruction = Instruction::fromBytes('a1', 'e0');
        $this->assertTrue($this->registerDecoder->supports($instruction));
        $this->registerDecoder->execute($instruction);
        $this->assertSame('1e0', $this->registers->getIndexRegister());
    }
}
