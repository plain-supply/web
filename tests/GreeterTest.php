<?php

declare(strict_types=1);

namespace PlainSupply\Web\Tests;

use PlainSupply\Web\Greeter;
use PHPUnit\Framework\TestCase;

class GreeterTest extends TestCase
{
    private Greeter $greeter;

    protected function setUp(): void
    {
        $this->greeter = new Greeter('World');
    }

    public function testGreetWithDefaultName(): void
    {
        $result = $this->greeter->greet();
        $this->assertEquals('Hello, World!', $result);
    }

    public function testGreetWithCustomName(): void
    {
        $result = $this->greeter->greet('Alice');
        $this->assertEquals('Hello, Alice!', $result);
    }

    public function testGreetMultiple(): void
    {
        $names = ['Alice', 'Bob', 'Charlie'];
        $expected = [
            'Hello, Alice!',
            'Hello, Bob!',
            'Hello, Charlie!',
        ];

        $result = $this->greeter->greetMultiple($names);
        $this->assertEquals($expected, $result);
    }

    public function testConstructorWithCustomDefault(): void
    {
        $customGreeter = new Greeter('Universe');
        $result = $customGreeter->greet();
        $this->assertEquals('Hello, Universe!', $result);
    }
}
