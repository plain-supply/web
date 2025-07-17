<?php

declare(strict_types=1);

namespace PlainSupply\Web;

class Greeter
{
    private string $defaultName;

    public function __construct(string $defaultName = 'World')
    {
        $this->defaultName = $defaultName;
    }

    public function greet(?string $name = null): string
    {
        $recipient = $name ?? $this->defaultName;
        return sprintf('Hello, %s!', $recipient);
    }

    /**
     * @param array<string> $names
     *
     * @return array<string>
     */
    public function greetMultiple(array $names): array
    {
        return array_map([$this, 'greet'], $names);
    }
}
