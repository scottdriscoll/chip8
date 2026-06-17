<?php

namespace App\Tui;

use App\Systems\Display;

final readonly class Chip8ViewStateFactory
{
    public function __construct(
        private Display $display,
    ) {
    }

    public function create(bool $stopped = false): Chip8ViewState
    {
        $signature = hash('xxh3', $this->display->signature().'|'.($stopped ? '1' : '0'));

        return new Chip8ViewState($this->display->getRows(), $signature, $stopped);
    }
}
