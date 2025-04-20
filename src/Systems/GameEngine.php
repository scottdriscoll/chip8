<?php

namespace App\Systems;

class GameEngine
{
    public function __construct(
        private readonly Memory $memory,
        private readonly Registers $registers,
    ) {

    }

    public function run(string $romPath): void
    {
        $this->memory->loadRom($romPath);

    }

}
