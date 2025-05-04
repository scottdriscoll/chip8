<?php

namespace App\Tests\Systems;

use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class RegistersTest extends TestCase
{
    private Registers $registers;

    protected function setUp(): void
    {
        $this->registers = new Registers();
    }

    public function testGeneralRegistersInRange(): void
    {
        $this->registers->setGeneralRegister(0, 0x00);

        $this->assertEquals(0x00, $this->registers->getGeneralRegister(0));
    }

    public function testGeneralRegistersOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->registers->setGeneralRegister(100, 0x00);
    }

    public function testIndexRegisterInRange(): void
    {
        $this->registers->setIndexRegister(0x00);
        $this->assertEquals(0x00, $this->registers->getIndexRegister());
    }
}
