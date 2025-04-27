<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface DecoderInterface
{
    public function supports(Instruction $instruction): bool;
    public function execute(Instruction $instruction): void;
    public function name(): string;
    public function setDebugOutputPath(string $path): void;
}
