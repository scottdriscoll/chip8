<?php

namespace App\Tui;

final readonly class Chip8ViewState
{
    /**
     * @param array<int, array<int, bool>> $rows
     */
    public function __construct(
        public array $rows,
        public string $signature,
        public bool $stopped = false,
    ) {
    }
}
