<?php

namespace App\Tui;

use App\Systems\Decoders\KeyboardDecoder;
use App\Systems\GameClock;
use App\Systems\GameEngine;
use App\Systems\Timer;
use App\Tui\Widget\Chip8DisplayWidget;
use Symfony\Component\Tui\Event\InputEvent;
use Symfony\Component\Tui\Input\Key;
use Symfony\Component\Tui\Input\KeyParser;
use Symfony\Component\Tui\Tui;

final readonly class Chip8TuiRunner
{
    public function __construct(
        private GameEngine $gameEngine,
        private KeyboardDecoder $keyboardDecoder,
        private Timer $timer,
        private GameClock $gameClock,
        private Chip8ViewStateFactory $stateFactory,
    ) {
    }

    public function run(string $romPath): void
    {
        $this->gameEngine->load($romPath);

        $tui = new Tui();
        $display = new Chip8DisplayWidget();
        $display->setState($this->stateFactory->create());
        $tui->add($display);

        $stopped = false;
        $soundActive = false;
        $lastBellAt = 0.0;
        $keyParser = new KeyParser();

        $tui->addListener(function (InputEvent $event) use ($keyParser, $tui, $display, &$stopped): void {
            $key = $keyParser->parse($event->getData())['key'] ?? null;

            if (Key::ESCAPE === $key || Key::ctrl('c') === $key) {
                $stopped = true;
                $this->gameEngine->stop();
                $tui->stop();
                $event->stopPropagation();

                return;
            }

            if (null !== $key && $this->keyboardDecoder->pressKey($key)) {
                $event->stopPropagation();
            }

            if ($display->setState($this->stateFactory->create($stopped))) {
                $tui->requestRender();
            }
        });

        $tui->onTick(function () use ($tui, $display, &$stopped, &$soundActive, &$lastBellAt): bool {
            $running = $this->gameEngine->tick();
            $now = $this->gameClock->now();
            $timerSoundActive = $this->timer->getSoundTimer() > 0;

            if ($timerSoundActive && (!$soundActive || ($now - $lastBellAt) >= 0.15)) {
                $tui->getTerminal()->bell();
                $lastBellAt = $now;
            }
            $soundActive = $timerSoundActive;

            if (!$running) {
                $stopped = true;
            }

            if ($display->setState($this->stateFactory->create($stopped))) {
                $tui->requestRender();
            }

            if (!$running) {
                $tui->stop();
            }

            return $running;
        });

        $tui->run();
    }
}
