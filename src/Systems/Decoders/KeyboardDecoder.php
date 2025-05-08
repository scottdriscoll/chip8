<?php

namespace App\Systems\Decoders;

use App\Models\Instruction;
use App\Systems\Decoders\AbstractDecoder;
use App\Systems\Decoders\DecoderInterface;
use App\Systems\ProgramCounter;
use App\Systems\Registers;

/**
 * The Original keypad was laid out as such:
 *
 * 123C
 * 456D
 * 789E
 * A0BF
 *
 * However this is way too annoying for modern keyboards. We will use the left side
 * of the qwerty keyboard and map to the following to the above:
 *
 * 1234
 * QWER
 * ASDF
 * ZXCV
 */
class KeyboardDecoder extends AbstractDecoder implements DecoderInterface
{
    private const float DURATION = 0.06;

    /**
     * @var array<int|string,int> $mapping
     */
    private array $mapping = [
        '1' => 0x1,
        '2' => 0x2,
        '3' => 0x3,
        '4' => 0xc,
        'q' => 0x4,
        'w' => 0x5,
        'e' => 0x6,
        'r' => 0xd,
        'a' => 0x7,
        's' => 0x8,
        'd' => 0x9,
        'f' => 0xe,
        'z' => 0xa,
        'x' => 0x0,
        'c' => 0xb,
        'v' => 0xf,
    ];

    /**
     * I don't know how to get proper key down/up events out of a terminal without
     * having to install 3rd party packages.
     *
     * Once I read a key from the input, subsequent reads will be null until the OS
     * sends another keydown to the terminal, until the user releases the key.
     *
     * So to overcome this, when a key is pressed, we will consider it pressed for DURATION ms.
     * If another or even the same key is pressed in this time, the timer will be reset
     * and the new (or current) key will be logged here.
     */
    private ?int $keyDown = null;

    private float $time;

    public function __construct(
        private readonly ProgramCounter $programCounter,
        private readonly Registers $registers,
    ) {
        $this->time = microtime(true);
    }

    public function supports(Instruction $instruction): bool
    {
        return $instruction->nibble1 === 0xe || ($instruction->nibble1 === 0xf && $instruction->byte2 === 0x0a);
    }

    public function execute(Instruction $instruction): void
    {
        if ($instruction->nibble1 === 0xe) {
            $this->skipIfKey($instruction);
        } else {
            $this->getKey($instruction);
        }
    }

    public function testKeyDown(): void
    {
        $key = $this->mapping[$this->nonBlockingRead()] ?? null;
        if ($key !== null) {  // key is pressed, just update now and restart timer
            $this->keyDown = $key;
            $this->time = microtime(true);
        } elseif ($this->keyDown !== null && ((microtime(true) - $this->time) >= self::DURATION)) {
            // no key is pressed, only erase after DURATION ms
            $this->keyDown = null;
        }
    }

    public function name(): string
    {
        return 'Keyboard Decoder';
    }

    private function skipIfKey(Instruction $instruction): void
    {
        $vx = $this->registers->getGeneralRegister($instruction->nibble2);

        if ($instruction->byte2 === 0x9e && $this->keyDown === $vx) {
            $this->programCounter->increment();
        } elseif ($instruction->byte2 === 0xa1 && $this->keyDown !== $vx) {
            $this->programCounter->increment();
        }
    }

    /**
     * getKey() waits for a key to be pressed by simply decrementing
     * the program counter until one is.
     */
    private function getKey(Instruction $instruction): void
    {
        if ($this->keyDown === null) {
            $this->programCounter->decrement();

            return;
        }

        $this->registers->setGeneralRegister($instruction->nibble2, $this->keyDown);
    }

    private function nonBlockingRead(): ?string
    {
        $read = [STDIN];
        $write = [];
        $except = [];
        $result = stream_select($read, $write, $except, 0);

        if ($result === false || $result === 0) {
            return null;
        }

        $key = stream_get_line(STDIN, 1);

        return $key ?: null;
    }
}
