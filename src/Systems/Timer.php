<?php

namespace App\Systems;

class Timer
{
    private const FREQUENCY = 1000 / 60;
    private float $startTime = 0;
    private int $delayTimer = 0;
    private int $soundTimer = 0;

    public function __construct()
    {

    }

    /**
     * We need to decrement both timers by 1 60 times per second.
     */
    public function elapsed(float $elapsed): void
    {
        $this->startTime += $elapsed;
        if ($this->startTime >= self::FREQUENCY) {
            $this->startTime -= self::FREQUENCY;
            if ($this->delayTimer > 0) {
                $this->delayTimer--;
            }
            if ($this->soundTimer > 0) {
                $this->soundTimer--;
            }
        }
    }

    public function getDelayTimer(): int
    {
        return $this->delayTimer;
    }

    public function setDelayTimer(int $delayTimer): void
    {
        $this->delayTimer = $delayTimer;
    }

    public function getSoundTimer(): int
    {
        return $this->soundTimer;
    }

    public function setSoundTimer(int $soundTimer): void
    {
        $this->soundTimer = $soundTimer;
    }
}
