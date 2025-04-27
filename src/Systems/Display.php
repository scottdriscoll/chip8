<?php

namespace App\Systems;

use App\Helpers\OutputHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Service\Attribute\Required;

class Display
{
    public const WIDTH = 64;
    public const HEIGHT = 32;
    private const HORIZONTAL_SCALE = 2;
    private const SCREEN_UNIT = '<bg=green> </bg=green>';
    private const ERASE_UNIT = '<bg=black> </bg=black>';

    private ?OutputInterface $output = null;

    /**
     * @var array<int, array<int, ScreenUnit>> $screen
     */
    private array $screen;

    public function __construct(
        private readonly OutputHelper $outputHelper,
    ) {
    }

    #[Required]
    public function initialize(): void
    {
        for ($y = 0; $y < self::HEIGHT; $y++) {
            for ($x = 0; $x < self::WIDTH * self::HORIZONTAL_SCALE; $x++) {
                $this->screen[$y][$x] = new ScreenUnit(nextValue: self::ERASE_UNIT);
            }
        }
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    public function clearScreen()
    {
        foreach ($this->screen as $row) {
            foreach ($row as $unit) {
                $unit->setNext(self::ERASE_UNIT);
            }
        }
    }

    public function nextFrame()
    {
        foreach ($this->screen as $row) {
            foreach ($row as $unit) {
                $unit->nextFrame();
            }
        }
    }

    public function draw(): void
    {
        $this->outputHelper->clear();
        foreach ($this->screen as $y => $row) {
            foreach ($row as $x => $unit) {
                if ($unit->hasChanged()) {
                    $this->outputHelper->moveCursorUp(100);
                    $this->outputHelper->moveCursorFullLeft();
                    if ($y > 0) {
                        $this->outputHelper->moveCursorDown($y);
                    }
                    if ($x > 0) {
                        $this->outputHelper->moveCursorRight($x);
                    }
                    $this->outputHelper->write($unit->getNext());
                }
            }
        }
        $this->nextFrame();
        $this->outputHelper->dump();
    }

    public function pixelEnabled(int $x, int $y): bool
    {
        if ($x < 0 || $x >= self::WIDTH || $y < 0 || $y >= self::HEIGHT) {
            return false;
        }

        return $this->screen[$y][$x * self::HORIZONTAL_SCALE]->getCurrent() === self::SCREEN_UNIT;
    }

    public function setEnabled(int $x, int $y, bool $enabled): void
    {
        if ($x < 0 || $x >= self::WIDTH || $y < 0 || $y >= self::HEIGHT) {
            return;
        }

        $newValue = $enabled ? self::SCREEN_UNIT : self::ERASE_UNIT;
        for ($setX = $x * self::HORIZONTAL_SCALE; $setX < $x * self::HORIZONTAL_SCALE + self::HORIZONTAL_SCALE; $setX++) {
            $this->screen[$y][$setX]->setNext($newValue);
        }
    }
}
