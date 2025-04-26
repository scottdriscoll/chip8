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
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');

        try {
            register_shutdown_function('\App\Helpers\OutputHelper::showCursor');
            $this->gameEngine->setOutput($output);
            $this->gameEngine->run($path);
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }

        if ($input->getOption('option1')) {
            // ...
        }

        return Command::SUCCESS;
    }
}
