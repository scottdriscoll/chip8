<?php

namespace App\Helpers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Service\Attribute\Required;

class OutputHelper
{
    private OutputInterface $output;

    private string $string = '';

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function clear(): void
    {
        $this->string = '';
    }

    public function dump(): void
    {
        $this->output->write($this->string);
        //var_dump($this->string);
    }

    public function write(string $msg): void
    {
        $this->string .= $msg;
    }

    public function writeln(string $msg): void
    {
        $this->string .= "$msg\n";
    }

    public function moveCursorDown(int $lines): void
    {
        $this->string .= sprintf("\033[%dB", $lines);
    }

    public function moveCursorUp(int $lines): void
    {
        $this->string .= sprintf("\033[%dA", $lines);
    }

    public function moveCursorRight(int $spaces): void
    {
        $this->string .= sprintf("\033[%dC", $spaces);
    }

    public function moveCursorFullLeft(): void
    {
        $this->string .= "\x0D";
    }

    #[Required]
    public function disableKeyboardOutput(): void
    {
        shell_exec('stty -icanon -echo');
    }

    #[Required]
    public function hideCursor(): void
    {
        printf("\e[?25l");
    }

    static public function showCursor(): void
    {
        echo sprintf("\033[%dB", 50);
        echo "\x0D";
        shell_exec('stty icanon echo');
        printf("\e[?25h");
    }
}
