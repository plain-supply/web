<?php

namespace PlainSupply\Web\Console;

class Application
{
    private array $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        $this->commands = [
            'start' => [
                'description' => 'Start the PHP development server',
                'handler' => [$this, 'startServer']
            ],
            'stop' => [
                'description' => 'Stop the PHP development server',
                'handler' => [$this, 'stopServer']
            ],
            'status' => [
                'description' => 'Check server status',
                'handler' => [$this, 'serverStatus']
            ]
        ];
    }

    public function run(array $argv): void
    {
        $script = array_shift($argv);
        $command = array_shift($argv) ?? 'help';

        if ($command === 'help' || $command === '--help' || $command === '-h') {
            $this->showHelp();
            return;
        }

        if (!isset($this->commands[$command])) {
            echo "Error: Unknown command '{$command}'\n";
            $this->showHelp();
            exit(1);
        }

        call_user_func($this->commands[$command]['handler'], $argv);
    }

    private function showHelp(): void
    {
        echo "Usage: php app [command] [options]\n\n";
        echo "Available commands:\n";
        foreach ($this->commands as $name => $info) {
            echo "  {$name}" . str_repeat(' ', 20 - strlen($name)) . $info['description'] . "\n";
        }
        echo "\nOptions:\n";
        echo "  --host=HOST     The host to serve on (default: localhost)\n";
        echo "  --port=PORT     The port to serve on (default: 8000)\n";
    }

    private function startServer(array $args): void
    {
        $options = $this->parseOptions($args);
        $host = $options['host'] ?? 'localhost';
        $port = $options['port'] ?? '8000';
        $publicDir = dirname(__DIR__, 2) . '/public';

        if (!is_dir($publicDir)) {
            echo "Error: Public directory not found at {$publicDir}\n";
            exit(1);
        }

        echo "Starting PHP development server on http://{$host}:{$port}...\n";
        echo "Document root: {$publicDir}\n";
        echo "Press Ctrl+C to stop the server\n\n";

        $command = sprintf('php -S %s:%s -t %s', $host, $port, escapeshellarg($publicDir));
        passthru($command);
    }

    private function stopServer(array $args): void
    {
        echo "To stop the server, press Ctrl+C in the terminal where it's running.\n";
    }

    private function serverStatus(array $args): void
    {
        $options = $this->parseOptions($args);
        $host = $options['host'] ?? 'localhost';
        $port = $options['port'] ?? '8000';

        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($connection) {
            echo "Server is running on http://{$host}:{$port}\n";
            fclose($connection);
        } else {
            echo "Server is not running on http://{$host}:{$port}\n";
        }
    }

    private function parseOptions(array $args): array
    {
        $options = [];
        foreach ($args as $arg) {
            if (strpos($arg, '--') === 0) {
                $parts = explode('=', substr($arg, 2), 2);
                if (count($parts) === 2) {
                    $options[$parts[0]] = $parts[1];
                }
            }
        }
        return $options;
    }
}