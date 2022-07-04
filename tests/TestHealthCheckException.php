<?php

namespace Cego\ServiceHealthChecking\Tests;

use Exception;
use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\BaseHealthCheck;

class TestHealthCheckException extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected function getDescription(): string
    {
        return 'This is a test health check that throws an exception';
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    protected function check(): HealthStatus
    {
        throw new Exception('Oh no...');
    }
}
