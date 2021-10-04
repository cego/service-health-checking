<?php

namespace Cego\ServiceHealthChecking\Interfaces;

interface HealthCheck
{
    /**
     * The main health check method. Returns true on success and false on failure.
     *
     * @return bool
     */
    public function check(): bool;

    /**
     * Get the error message associated with a failed check.
     *
     * @return string
     */
    public function getErrorMessage(): string;
}
