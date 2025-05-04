<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Registers;
use App\Systems\Timer;

class TimerDecoder extends AbstractDecoder implements DecoderInterface
{
    public function __construct(
        private readonly Timer $timer,
        private readonly Registers $registers,
    ) {
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 'f' && in_array($instruction->byte2, ['07',  '15', '18']);
    }

    public function execute(Instruction $instruction): void
    {
        switch ($instruction->byte2) {
            case '07':
                $this->registers->setGeneralRegister($instruction->nibble2Int, dechex($this->timer->getDelayTimer()));
                break;
            case '15':
                $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
                $this->timer->setDelayTimer($vx);
                break;
            case '18':
                $vx = hexdec($this->registers->getGeneralRegister($instruction->nibble2Int));
                $this->timer->setSoundTimer($vx);
                break;
        }
    }

    public function name(): string
    {
        return 'Timer Decoder';
    }
}
