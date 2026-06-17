<?php

namespace App\Systems;

use App\Systems\Decoders\KeyboardDecoder;

class GameEngine
{
    private const float TARGET_IPS = 700;
    private const float FRAME_DURATION = 1 / self::TARGET_IPS;

    private string $debugOutputPath = '';
    private ?int $maxCycles = null;
    private int $counter = 0;
    private bool $running = false;
    private float $lastInstructionTime = 0.0;
    private float $lastTimerTime = 0.0;

    public function __construct(
        private readonly Memory $memory,
        private readonly Decoder $decoder,
        private readonly ProgramCounter $programCounter,
        private readonly Timer $timer,
        private readonly KeyboardDecoder $keyboard,
    ) {
    }

    public function run(string $romPath): void
    {
        $this->load($romPath);
        while ($this->tick()) {
            usleep(1000);
        }
    }

    public function load(string $romPath): void
    {
        $this->memory->loadRom($romPath);
        $this->counter = 0;
        $this->running = true;
        $this->lastInstructionTime = microtime(true);
        $this->lastTimerTime = $this->lastInstructionTime;
    }

    public function tick(?float $now = null): bool
    {
        if (!$this->running) {
            return false;
        }

        $now ??= microtime(true);
        $this->keyboard->tick();
        $this->timer->elapsed(max(0, $now - $this->lastTimerTime));
        $this->lastTimerTime = $now;

        while (($now - $this->lastInstructionTime) >= self::FRAME_DURATION) {
            $this->executeInstruction();
            $this->lastInstructionTime += self::FRAME_DURATION;

            if ($this->maxCycles && $this->counter >= $this->maxCycles) {
                $this->running = false;

                return false;
            }
        }

        return true;
    }

    public function stop(): void
    {
        $this->running = false;
    }

    private function executeInstruction(): void
    {
        $instruction = $this->memory->fetchInstruction($this->programCounter->get());
        $this->programCounter->increment();
        $this->counter++;

        $decoder = $this->decoder->decodeInstruction($instruction);
        if ($this->debugOutputPath) {
            file_put_contents($this->debugOutputPath, "\n" . $this->counter . ' ' . $instruction . ' ' . $decoder->name() . "\n", FILE_APPEND);
        }
        $decoder->execute($instruction);
    }

    public function setDebugOutputPath(string $path): void
    {
        $this->debugOutputPath = $path;
        $this->decoder->setDebugOutputPath($path);
        if ($this->debugOutputPath) {
            file_put_contents($this->debugOutputPath, "-----------------------\n", FILE_APPEND);
        }
    }

    public function setMaxCycles(int $maxCycles): void
    {
        $this->maxCycles = $maxCycles;
    }
}
