<?php

namespace App\Systems;

use App\Models\Instruction;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;

class Memory
{
    const FONT_START = 0;   // The first 80 bytes are reserved for fonts.
    const FONT_SIZE = 5;    // Fonts are always 5 bytes long.
    const ROM_START = 512;  // The actual rom starts at byte 512.
    const MEMORY_SIZE = 4096;

    /**
     * @var array<int, int> $memory
     */
    private array $memory;

    /**
     * @var array<int, int> $fontIndexes
     */
    private array $fontIndexes = [];

    public function __construct()
    {
        $this->memory = array_fill(0, self::MEMORY_SIZE, 0);
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
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('ROM file does not exist.');
        }
        $hex = unpack("H*", file_get_contents($path));
        $hex = current($hex);
        $start = self::ROM_START;

        foreach (str_split($hex, 2) as $byte) {
            $this->memory[$start++] = hexdec($byte);
        }
    }

    public function loadFont(string $path): void
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('Font file does not exist.');
        }
        $fontCharacters = json_decode(file_get_contents($path), true);
        $start = self::FONT_START;

        foreach ($fontCharacters as $character => $bytes) {
            $this->fontIndexes[hexdec($character)] = $start;

            foreach ($bytes as $byte) {
                $this->memory[$start++] = hexdec($byte);
            }
        }
    }

    public function getFontIndex(int $character): int
    {
        if (!isset($this->fontIndexes[$character])) {
            throw new \InvalidArgumentException('Font character not found: ' . $character);
        }

        return $this->fontIndexes[$character];
    }

    /**
     * @return array<int, int>
     */
    public function getMemory(): array
    {
        return $this->memory;
    }

    public function fetchInstruction(int $address): Instruction
    {
        return Instruction::fromBytes($this->memory[$address], $this->memory[$address+1]);
    }

    public function getMemoryValue(int $address): int
    {
        return $this->memory[$address];
    }

    public function setMemoryValue(int $address, int $value): void
    {
        $this->memory[$address] = $value;
    }
}
