<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use PlainSupply\Web\Greeter;

// Create a greeter instance
$greeter = new Greeter('PHP Developer');

// Basic greeting
echo $greeter->greet() . PHP_EOL;
echo $greeter->greet('Jeroen') . PHP_EOL;

// Greet multiple people
$names = ['Alice', 'Bob', 'Charlie'];
$greetings = $greeter->greetMultiple($names);

echo PHP_EOL . "Multiple greetings:" . PHP_EOL;
foreach ($greetings as $greeting) {
    echo "- " . $greeting . PHP_EOL;
}