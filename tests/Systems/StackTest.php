<?php

namespace App\Tests\Systems;

use App\Systems\Stack;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    public function testStack(): void
    {
        $stack = new Stack();
        $stack->push(1);
        $stack->push(2);
        $this->assertSame(2, $stack->pop());
        $this->assertSame(1, $stack->pop());
    }
}
