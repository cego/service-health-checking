<?php

namespace Cego\ServiceHealthChecking\Tests;

class TestHealthCheck implements \Cego\ServiceHealthChecking\Interfaces\HealthCheck
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string
    {
    }
}
