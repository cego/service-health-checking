<?php

namespace Cego\ServiceHealthChecking\Tests;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheckWarn extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected function getDescription(): string
    {
        return 'This is a test health check that WARNS';
    }

    /**
     * @inheritdoc
     */
    protected function check(): HealthStatus
    {
        return HealthStatus::warn()->setMessage('It warns');
    }
}
