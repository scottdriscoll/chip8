<?php

namespace App\Systems;

use App\Helpers\OutputHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GameEngine
{
    private string $debugOutputPath = '';
    private ?int $maxCycles = null;

    public function __construct(
        private readonly Memory $memory,
        private readonly Decoder $decoder,
        private readonly ProgramCounter $programCounter,
        private readonly Display $display,
        private readonly OutputHelper $outputHelper,
        private readonly Timer $timer,
        #[Autowire(env: 'APP_ENV')] private readonly string $appEnv
    ) {
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->display->setOutput($output);
        $this->outputHelper->setOutput($output);
    }

    public function run(string $romPath): void
    {
        if ($this->display->getOutput() === null) {
            throw new \Exception('Output not set.');
        }

        $this->memory->loadRom($romPath);
        $counter = 0;

        while (true) {
            $startTimer = microtime(true);
            $instruction = $this->memory->fetchInstruction($this->programCounter->get());
            $this->programCounter->increment();
            $counter++;

            try {
                $decoder = $this->decoder->decodeInstruction($instruction);
                if ($this->debugOutputPath) {
                    file_put_contents($this->debugOutputPath, "\n" . $counter . ' ' . $instruction->byte1 . $instruction->byte2 . ' ' . $decoder->name() . "\n", FILE_APPEND);
                }
                $decoder->execute($instruction);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                break;
            }

            if ($this->maxCycles && $counter >= $this->maxCycles) {
                break;
            }

            $elapsed = (microtime(true) - $startTimer) * 1000;

            $this->timer->elapsed($elapsed);

            if ($this->appEnv !== 'test') {
                usleep(2000 - $elapsed);
            }
        }
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
