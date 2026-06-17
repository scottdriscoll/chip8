<?php

namespace App\Tui\Widget;

use App\Systems\Display;
use App\Tui\Chip8ViewState;
use Symfony\Component\Tui\Render\RenderContext;
use Symfony\Component\Tui\Widget\AbstractWidget;

final class Chip8DisplayWidget extends AbstractWidget
{
    private ?Chip8ViewState $state = null;
    private ?string $lastSignature = null;

    public function setState(Chip8ViewState $state): bool
    {
        if ($this->lastSignature === $state->signature) {
            return false;
        }

        $this->state = $state;
        $this->lastSignature = $state->signature;
        $this->invalidate();

        return true;
    }

    /**
     * @return string[]
     */
    public function render(RenderContext $context): array
    {
        $columns = max(1, $context->getColumns());

        if (null === $this->state) {
            return [$this->plainLine('Loading CHIP-8...', $columns)];
        }

        $lines = [
            $columns >= 6 ? "\033[1mCHIP-8\033[0m" : $this->plainLine('CHIP-8', $columns),
            $this->plainLine('Keypad: 1234 / QWER / ASDF / ZXCV. Esc quits.', $columns),
            '',
        ];

        $displayColumns = min(Display::WIDTH * 2, $columns);
        for ($y = 0; $y < Display::HEIGHT; $y++) {
            $line = '';
            for ($x = 0; $x < Display::WIDTH && (($x + 1) * 2) <= $displayColumns; $x++) {
                $line .= $this->cell($this->state->rows[$y][$x] ?? false);
            }
            $lines[] = $line;
        }

        if ($this->state->stopped) {
            $lines[] = '';
            $lines[] = $columns >= 7 ? "\033[1;31mStopped\033[0m" : $this->plainLine('Stopped', $columns);
        }

        return $lines;
    }

    private function cell(bool $enabled): string
    {
        return $enabled ? "\033[42m  \033[0m" : "\033[40m  \033[0m";
    }

    private function plainLine(string $value, int $columns): string
    {
        return mb_substr($value, 0, $columns);
    }
}
