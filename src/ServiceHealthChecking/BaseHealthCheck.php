<?php

namespace Cego\ServiceHealthChecking;

use Exception;
use ReflectionClass;

abstract class BaseHealthCheck
{
    /**
     * Description of the health check.
     *
     * @return string
     */
    abstract protected function getDescription(): string;

    /**
     * The main health check method. Returns a HealthStatus object.
     *
     * Examples:
     * HealthStatus::pass()
     * HealthStatus::fail()->message('The health check failed')
     *
     * @return HealthStatus
     */
    abstract protected function check(): HealthStatus;

    /**
     * If this method returns true, the health check is skipped.
     *
     * @return bool
     */
    public function shouldSkip(): bool
    {
        return false;
    }

    /**
     * Gets the class name of the health check
     *
     * @return string
     */
    private function getName(): string
    {
        return (new ReflectionClass(static::class))->getShortName();
    }

    /**
     * Get the health check response
     *
     * @return HealthCheckResponse
     */
    final public function getResponse(): HealthCheckResponse
    {
        $description = '';

        try {
            $description = $this->getDescription();
            $status = $this->check();
        } catch (Exception $exception) {
            $status = HealthStatus::fail()->setMessage($exception);
        }

        return new HealthCheckResponse($status, $this->getName(), $description);
    }
}
