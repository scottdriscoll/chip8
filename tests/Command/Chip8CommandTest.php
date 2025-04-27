<?php

namespace App\Tests\Command;

use App\Systems\Display;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Chip8CommandTest extends KernelTestCase
{
    public function testIbmLogo(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('app:chip8');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'path' => 'tests/fixtures/roms/ibm_logo.ch8',
            '--max-cycles' => '20',
        ]);

        $commandTester->assertCommandIsSuccessful();

        /** @var Display $display */
        $display = self::getContainer()->get(Display::class);
        $this->assertSame($this->buildExpectedArray(), $display->getEnabledArray());
    }

    /**
     * @return array<int, array<int, int>>
     */
    private function buildExpectedArray(): array
    {
        $arr = [];

        foreach (file(__DIR__ . '/../fixtures/Chip8CommandTestInput.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            [$x, $y, $value] = array_map('trim', explode(',', $line));

            $x = (int) $x;
            $y = (int) $y;
            $value = (int) $value;

            $arr[$x][$y] = $value;
        }

        return $arr;
    }
}
