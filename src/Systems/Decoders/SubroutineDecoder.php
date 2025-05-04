<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\ProgramCounter;
use App\Systems\Stack;

class SubroutineDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Stack $stack,
        private readonly ProgramCounter $programCounter,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return ($instruction->byte1 === 0 && $instruction->byte2 === 0xee) || ($instruction->nibble1 === 0x2);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === 0x2) {
            $this->stack->push($this->programCounter->get());
            $this->programCounter->set($instruction->address);
        } else {
            $this->programCounter->set($this->stack->pop());
        }
    }

    public function name(): string
    {
        return 'Subroutine Decoder';
    }
}
