<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Logger;

class LoggerTest extends TestCase
{
    private Logger $logger;
    private string $testLogFile;

    protected function setUp(): void
    {
        $this->testLogFile = __DIR__ . '/../../logs/test.log';
        $this->logger = new Logger($this->testLogFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    public function testInfoLog(): void
    {
        $message = 'Test info message';
        $this->logger->info($message);

        $this->assertFileExists($this->testLogFile);
        $content = file_get_contents($this->testLogFile);
        $this->assertStringContainsString('INFO', $content);
        $this->assertStringContainsString($message, $content);
    }

    public function testErrorLog(): void
    {
        $message = 'Test error message';
        $this->logger->error($message);

        $this->assertFileExists($this->testLogFile);
        $content = file_get_contents($this->testLogFile);
        $this->assertStringContainsString('ERROR', $content);
        $this->assertStringContainsString($message, $content);
    }

    public function testWarningLog(): void
    {
        $message = 'Test warning message';
        $this->logger->warning($message);

        $this->assertFileExists($this->testLogFile);
        $content = file_get_contents($this->testLogFile);
        $this->assertStringContainsString('WARNING', $content);
        $this->assertStringContainsString($message, $content);
    }
}