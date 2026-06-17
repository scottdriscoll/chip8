<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\MathDecoder;
use App\Systems\Decoders\SkipConditionalDecoder;
use App\Systems\ProgramCounter;
use App\Systems\Registers;
use PHPUnit\Framework\TestCase;

class OpcodeSupportTest extends TestCase
{
    public function testSkipConditionalOnlySupportsZeroLowNibbleForRegisterComparisons(): void
    {
        $decoder = new SkipConditionalDecoder(new ProgramCounter(), new Registers());

        $this->assertTrue($decoder->supports(Instruction::fromBytes(0x51, 0x20)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x51, 0x21)));
        $this->assertTrue($decoder->supports(Instruction::fromBytes(0x91, 0x20)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x91, 0x21)));
    }

    public function testMathDecoderOnlySupportsDefinedEightSeriesOpcodes(): void
    {
        $decoder = new MathDecoder(new Registers());

        $this->assertTrue($decoder->supports(Instruction::fromBytes(0x81, 0x2e)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x81, 0x28)));
    }
}
