<?php

namespace Cego\ServiceHealthChecking\Tests;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheckPass extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected function getDescription(): string
    {
        return 'This is a test health check that PASSES';
    }

    /**
     * @inheritdoc
     */
    protected function check(): HealthStatus
    {
        return HealthStatus::pass();
    }
}
