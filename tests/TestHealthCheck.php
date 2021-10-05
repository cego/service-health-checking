<?php

namespace Cego\ServiceHealthChecking\Tests;

use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheck extends BaseHealthCheck
{
    /**
     * @var string
     */
    protected string $description = "This is a test health check";

    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        // Mock this
    }
}
