<?php

namespace Cego\ServiceHealthChecking\Tests;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheckFail extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected function getDescription(): string
    {
        return "This is a test health check that FAILS";
    }

    /**
     * @inheritdoc
     */
    protected function check(): HealthStatus
    {
        return HealthStatus::fail()->setMessage('It failed');
    }
}
