<?php

namespace App\Systems\Decoders;

abstract class AbstractDecoder
{
    private string $debugOutputPath = '';

    public function setDebugOutputPath(string $path): void
    {
        $this->debugOutputPath = $path;
    }

    public function writeDebugOutput(string $output): void
    {
        if ($this->debugOutputPath) {
            file_put_contents($this->debugOutputPath, $output, FILE_APPEND);
        }
    }
}