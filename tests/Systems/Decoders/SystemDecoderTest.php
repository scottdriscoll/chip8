<?php

namespace App\Tests\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\SystemDecoder;
use PHPUnit\Framework\TestCase;

class SystemDecoderTest extends TestCase
{
    public function testSupportsOriginalSystemInstructionsAsNoOps(): void
    {
        $decoder = new SystemDecoder();

        $this->assertTrue($decoder->supports(Instruction::fromBytes(0x00, 0x00)));
        $this->assertTrue($decoder->supports(Instruction::fromBytes(0x01, 0x23)));
    }

    public function testDoesNotClaimKnownZeroSeriesInstructions(): void
    {
        $decoder = new SystemDecoder();

        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x00, 0xe0)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x00, 0xee)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x00, 0xfe)));
        $this->assertFalse($decoder->supports(Instruction::fromBytes(0x00, 0xff)));
    }
}
