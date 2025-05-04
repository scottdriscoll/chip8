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
        return $instruction->nibble1 === 0xf && in_array($instruction->byte2, [0x07,  0x15, 0x18]);
    }

    public function execute(Instruction $instruction): void
    {
        switch ($instruction->byte2) {
            case 0x07:
                $this->registers->setGeneralRegister($instruction->nibble2, $this->timer->getDelayTimer());
                break;
            case 0x15:
                $vx = $this->registers->getGeneralRegister($instruction->nibble2);
                $this->timer->setDelayTimer($vx);
                break;
            case 0x18:
                $vx = $this->registers->getGeneralRegister($instruction->nibble2);
                $this->timer->setSoundTimer($vx);
                break;
        }
    }

    public function name(): string
    {
        return 'Timer Decoder';
    }
}
