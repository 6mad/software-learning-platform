<?php

namespace App;

class Config
{
    private array $config = [];

    public function __construct(string $configPath = null)
    {
        if ($configPath && file_exists($configPath)) {
            $this->config = require $configPath;
        }

        // Load environment variables
        $this->loadEnv();
    }

    private function loadEnv(): void
    {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    if (!array_key_exists($key, $_ENV)) {
                        $_ENV[$key] = $value;
                        $_SERVER[$key] = $value;
                    }
                }
            }
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $_ENV[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }
}