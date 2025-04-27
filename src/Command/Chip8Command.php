<?php

namespace App\Command;

use App\Systems\GameEngine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:chip8',
    description: 'Chip8 emulator',
)]
class Chip8Command extends Command
{
    public function __construct(private readonly GameEngine $gameEngine)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'Path to rom', 'tests/fixtures/roms/ibm_logo.ch8')
            ->addArgument('debug-output-path', InputArgument::OPTIONAL, 'Path to debug output')
            ->addOption('max-cycles', 'm', InputOption::VALUE_OPTIONAL, 'Max cycles');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');
        $debugPath = $input->getArgument('debug-output-path');
        $maxCycles = $input->getOption('max-cycles');

        try {
            register_shutdown_function('\App\Helpers\OutputHelper::showCursor');
            if ($debugPath) {
                $this->gameEngine->setDebugOutputPath($debugPath);
            }
            if ($maxCycles) {
                $this->gameEngine->setMaxCycles($maxCycles);
            }
            $this->gameEngine->setOutput($output);
            $this->gameEngine->run($path);
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
