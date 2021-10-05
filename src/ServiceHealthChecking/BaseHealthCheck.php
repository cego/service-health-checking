<?php

namespace Cego\ServiceHealthChecking;

use ReflectionClass;

abstract class BaseHealthCheck
{
    /**
     * Description of the health check.
     *
     * @var string
     */
    protected string $description;

    /**
     * Optional
     *
     * The error message will be displayed as part of the response if the check fails.
     * This is useful for returning code exception messages.
     *
     * @var string
     */
    protected string $errorMessage = '';

    /**
     * The main health check method. Returns a HealthStatus object.
     *
     * @return HealthStatus
     */
    abstract public function check(): HealthStatus;

    /**
     * Gets the description
     *
     * @return string
     */
    final public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Gets the class name of the health check
     *
     * @return string
     */
    final public function getName(): string
    {
        (new ReflectionClass(static::class))->getShortName();
    }

    /**
     * Gets the error message
     *
     * @return string
     */
    final public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
