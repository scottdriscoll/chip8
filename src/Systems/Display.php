<?php

namespace App\Systems;

use Symfony\Contracts\Service\Attribute\Required;

class Display
{
    public const WIDTH = 64;
    public const HEIGHT = 32;

    /**
     * @var array<int, array<int, bool>> $screen
     */
    private array $screen = [];

    #[Required]
    public function initialize(): void
    {
        $this->clearScreen();
    }

    public function clearScreen(): void
    {
        for ($y = 0; $y < self::HEIGHT; $y++) {
            for ($x = 0; $x < self::WIDTH; $x++) {
                $this->screen[$y][$x] = false;
            }
        }
    }

    public function draw(): void
    {
        // Rendering is handled by Symfony TUI. The emulator mutates this buffer.
    }

    public function pixelEnabled(int $x, int $y): bool
    {
        if ($x < 0 || $x >= self::WIDTH || $y < 0 || $y >= self::HEIGHT) {
            return false;
        }

        return $this->screen[$y][$x] ?? false;
    }

    public function setEnabled(int $x, int $y, bool $enabled): void
    {
        if ($x < 0 || $x >= self::WIDTH || $y < 0 || $y >= self::HEIGHT) {
            return;
        }

        $this->screen[$y][$x] = $enabled;
    }

    public function logScreen(): void
    {
        for ($y = 0; $y < self::HEIGHT; $y++) {
            for ($x = 0; $x < self::WIDTH; $x++) {
                echo "$y, $x, " . (int) $this->pixelEnabled($x, $y) . "\n";
            }
        }
    }

    /**
     * @return array<int, array<int, bool>>
     */
    public function getRows(): array
    {
        return $this->screen;
    }

    public function signature(): string
    {
        $parts = [];

        foreach ($this->screen as $row) {
            foreach ($row as $enabled) {
                $parts[] = $enabled ? '1' : '0';
            }
        }

        return hash('xxh3', implode('', $parts));
    }

    /**
     * @return array<int, array<int, int>>
     */
    public function getEnabledArray(): array
    {
        $arr = [];

        for ($y = 0; $y < self::HEIGHT; $y++) {
            for ($x = 0; $x < self::WIDTH * 2; $x++) {
                $arr[$y][$x] = (int) $this->pixelEnabled($y, $x);
            }
        }

        return $arr;
    }
}
