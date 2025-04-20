<?php

namespace App\Systems;

class GameEngine
{
    public function __construct(
        private readonly Memory $memory,
        private readonly Registers $registers,
        private readonly Decoder $decoder,
        private readonly ProgramCounter $programCounter,
    ) {
    }

    public function run(string $romPath): void
    {
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
