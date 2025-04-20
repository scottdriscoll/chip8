<?php

namespace App\Systems;

use Symfony\Contracts\Service\Attribute\Required;

class Memory
{
    const ROM_START = 512;
    const MEMORY_SIZE = 4096;

    /**
     * @var array<int, string|null> $memory
     */
    private array $memory;

    public function __construct()
    {

    }

    /**
     * Loads a ROM into memory.
     *
     * The first 512 bytes are reserved for fonts. The rest is for the ROM.
     * Memory is considered read and writable, and is always exactly 4096 bytes.
     */
    public function loadRom(string $path): void
    {
        $hex = unpack("H*", file_get_contents($path));
        $hex = current($hex);

        $this->memory = array_fill(0, self::ROM_START, null);

        foreach (str_split($hex, 2) as $byte) {
            $this->memory[] = $byte;
        }

        $this->memory = array_pad($this->memory, self::MEMORY_SIZE, null);
    }

    /**
     * @return array<int, string|null>
     */
    public function getMemory(): array
    {
        return $this->memory;
    }
}
