<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
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
        return in_array($instruction->nibble1, ['0', '2']);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === '2') {
            $this->stack->push($this->programCounter->get());
            $this->programCounter->set($instruction->addressInt);
        } else {
            $this->programCounter->set($this->stack->pop());
        }
    }

    public function name(): string
    {
        return 'Subroutine Decoder';
    }
}
