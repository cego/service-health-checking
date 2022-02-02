<?php

namespace Cego\ServiceHealthChecking\Tests;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheckSkip extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected function getDescription(): string
    {
        return 'This is a test health check that SKIPS';
    }

    /**
     * @inheritDoc
     */
    public function shouldSkip(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function check(): HealthStatus
    {
        return HealthStatus::pass();
    }
}
