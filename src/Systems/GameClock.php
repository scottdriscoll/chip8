<?php

namespace App\Systems;

final class GameClock
{
    public function now(): float
    {
        return hrtime(true) / 1_000_000_000;
    }
}
