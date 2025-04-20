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
        $this->registers->setGeneralRegister(0, '00');

        $this->assertEquals('00', $this->registers->getGeneralRegister(0));
    }

    public function testGeneralRegistersOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->registers->setGeneralRegister(100, '00');
    }

    public function testIndexRegisterInRange(): void
    {
        $this->registers->setIndexRegister('00');
        $this->assertEquals('00', $this->registers->getIndexRegister());
    }
}
