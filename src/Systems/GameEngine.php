<?php

namespace App\Systems;

use App\Helpers\OutputHelper;
use Symfony\Component\Console\Output\OutputInterface;

class GameEngine
{
    public function __construct(
        private readonly Memory $memory,
        private readonly Decoder $decoder,
        private readonly ProgramCounter $programCounter,
        private readonly Display $display,
        private readonly OutputHelper $outputHelper,
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
        $this->display->draw();
        $this->display->togglePixel(3, 5);
        $this->display->draw();return;

        $this->memory->loadRom($romPath);

        while (true) {
            $instruction = $this->memory->fetchInstruction($this->programCounter->get());
            $this->programCounter->increment();

            try {
                $decoder = $this->decoder->decodeInstruction($instruction);
                echo $instruction.' '.$decoder->name() . "\n";
                $decoder->execute($instruction);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                break;
            }

            echo "looping...\n";
            usleep(100000);
        }
    }
}
