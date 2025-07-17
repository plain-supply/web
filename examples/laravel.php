<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

echo "Laravel Framework Demo\n";
echo "=====================\n\n";

// 1. Collections
echo "1. Laravel Collections:\n";
$collection = collect([1, 2, 3, 4, 5]);
$multiplied = $collection->map(fn($item) => $item * 2);
echo "   Original: " . $collection->implode(', ') . "\n";
echo "   Doubled: " . $multiplied->implode(', ') . "\n";

$users = collect([
    ['name' => 'John', 'age' => 30],
    ['name' => 'Jane', 'age' => 25],
    ['name' => 'Bob', 'age' => 35],
]);
$avgAge = $users->avg('age');
echo "   Average age: $avgAge\n\n";

// 2. String Helpers
echo "2. String Helpers:\n";
$string = 'hello_world_from_laravel';
echo "   Original: $string\n";
echo "   Camel Case: " . Str::camel($string) . "\n";
echo "   Title Case: " . Str::title(str_replace('_', ' ', $string)) . "\n";
echo "   Slug: " . Str::slug($string) . "\n";
echo "   Random: " . Str::random(10) . "\n\n";

// 3. Array Helpers
echo "3. Array Helpers:\n";
$array = ['name' => 'John', 'age' => 30, 'address' => ['city' => 'New York', 'country' => 'USA']];
echo "   Get nested value: " . Arr::get($array, 'address.city') . "\n";
echo "   Has key: " . (Arr::has($array, 'address.country') ? 'yes' : 'no') . "\n";
$flattened = Arr::flatten($array);
echo "   Flattened: " . implode(', ', $flattened) . "\n\n";

// 4. Fluent Objects
echo "4. Fluent Objects:\n";
$fluent = new Fluent(['name' => 'Laravel', 'version' => 11]);
$fluent->framework = 'PHP';
echo "   Name: {$fluent->name}\n";
echo "   Version: {$fluent->version}\n";
echo "   Framework: {$fluent->framework}\n\n";

// 5. Dates with Carbon
echo "5. Carbon Date Handling:\n";
$now = Carbon::now();
echo "   Current time: " . $now->toDateTimeString() . "\n";
echo "   Human readable: " . $now->diffForHumans() . "\n";
echo "   Next week: " . $now->copy()->addWeek()->toDateString() . "\n";
echo "   Is weekend? " . ($now->isWeekend() ? 'yes' : 'no') . "\n\n";

// 6. Container/IoC
echo "6. Service Container:\n";
$container = new Container();

// Bind a simple service
$container->bind('greeter', function () {
    return new class {
        public function greet($name) {
            return "Hello from Laravel Container, $name!";
        }
    };
});

$greeter = $container->make('greeter');
echo "   " . $greeter->greet('Developer') . "\n\n";

// 7. Events
echo "7. Event System:\n";
$events = new Dispatcher($container);

$events->listen('user.registered', function ($user) {
    echo "   Event fired: User '{$user}' was registered!\n";
});

$events->dispatch('user.registered', ['John Doe']);
echo "\n";

// 8. Filesystem
echo "8. Filesystem Operations:\n";
$files = new Filesystem();
$tempFile = __DIR__ . '/temp_laravel.txt';
$files->put($tempFile, 'Laravel filesystem test');
echo "   File created: " . ($files->exists($tempFile) ? 'yes' : 'no') . "\n";
echo "   File size: " . $files->size($tempFile) . " bytes\n";
echo "   File content: " . $files->get($tempFile) . "\n";
$files->delete($tempFile);
echo "   File deleted: " . (!$files->exists($tempFile) ? 'yes' : 'no') . "\n\n";

// 9. Pipeline Pattern
echo "9. Pipeline Pattern:\n";
use Illuminate\Pipeline\Pipeline;

$result = (new Pipeline($container))
    ->send(5)
    ->through([
        function ($value, $next) {
            return $next($value * 2);
        },
        function ($value, $next) {
            return $next($value + 10);
        },
        function ($value, $next) {
            return $next($value / 2);
        },
    ])
    ->thenReturn();

echo "   Pipeline result: 5 -> *2 -> +10 -> /2 = $result\n\n";

// 10. Collection Advanced Features
echo "10. Advanced Collections:\n";
$products = collect([
    ['name' => 'Laptop', 'price' => 1200, 'category' => 'Electronics'],
    ['name' => 'Phone', 'price' => 800, 'category' => 'Electronics'],
    ['name' => 'Desk', 'price' => 300, 'category' => 'Furniture'],
    ['name' => 'Chair', 'price' => 150, 'category' => 'Furniture'],
]);

$grouped = $products->groupBy('category')->map(function ($items) {
    return [
        'count' => $items->count(),
        'total' => $items->sum('price'),
        'average' => $items->avg('price'),
    ];
});

echo "   Product statistics by category:\n";
foreach ($grouped as $category => $stats) {
    echo "   - $category: {$stats['count']} items, \${$stats['total']} total, \${$stats['average']} avg\n";
}

echo "\n✅ Laravel Framework is successfully loaded and working!\n";