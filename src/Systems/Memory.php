<?php

namespace App\Systems;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;

class Memory
{
    const FONT_START = 0;   // The first 80 bytes are reserved for fonts.
    const FONT_SIZE = 5;    // Fonts are always 5 bytes long.
    const ROM_START = 512;  // The actual rom starts at byte 512.
    const MEMORY_SIZE = 4096;

    /**
     * @var array<int, string|null> $memory
     */
    private array $memory;

    /**
     * @var array<string, string> $fontIndexes
     */
    private array $fontIndexes = [];

    public function __construct()
    {
        $this->memory = array_fill(0, self::MEMORY_SIZE, null);
    }

    #[Required]
    public function initalize(#[Autowire(env: 'string:DEFAULT_FONT_PATH')] string $fontPath): void
    {
        $this->loadFont($fontPath);
    }

    /**
     * Loads a ROM into memory.
     *
     * The first 80 bytes are reserved for fonts. The next up to 512 are unused. The rest is for the ROM.
     * Memory is considered read and writable, and is always exactly 4096 bytes.
     */
    public function loadRom(string $path): void
    {
        $hex = unpack("H*", file_get_contents($path));
        $hex = current($hex);
        $start = self::ROM_START;

        foreach (str_split($hex, 2) as $byte) {
            $this->memory[$start++] = strtolower($byte);
        }
    }

    public function loadFont(string $path): void
    {
        $fontCharacters = json_decode(file_get_contents($path), true);
        $start = self::FONT_START;

        foreach ($fontCharacters as $character => $bytes) {
            $this->fontIndexes[$character] = $start;

            foreach ($bytes as $byte) {
                $this->memory[$start++] = $byte;
            }
        }
    }

    /**
     * @return array<int, string|null>
     */
    public function getMemory(): array
    {
        return $this->memory;
    }
}
