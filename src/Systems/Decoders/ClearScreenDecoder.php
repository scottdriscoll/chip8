<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Display;

class ClearScreenDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Display $display,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->byte1 === '00' && $instruction->byte2 === 'e0';
    }

    public function execute(Instruction $instruction): void
    {
        $this->writeDebugOutput("Clearing screen\n");
        $this->display->clearScreen();
        $this->display->draw();
    }

    public function name(): string
    {
        return 'Clear Screen Decoder';
    }
}