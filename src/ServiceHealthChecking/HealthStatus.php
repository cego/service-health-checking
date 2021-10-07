<?php

namespace Cego\ServiceHealthChecking;

final class HealthStatus
{
    public const PASS = 0;
    public const WARNING = 1;
    public const FAIL = 2;

    /**
     * @var string[]
     */
    private array $statusText = [
        self::PASS    => 'pass',
        self::WARNING => 'warning',
        self::FAIL    => 'fail',
    ];

    /**
     * The current status code
     *
     * @var int
     */
    private int $statusCode = self::PASS;

    /**
     * Holds the status message
     *
     * @var string
     */
    private string $statusMessage = '';

    /**
     * Getter for the current status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Instantiates a HealthStatus with status PASS
     *
     * @return HealthStatus
     */
    public static function pass(): HealthStatus
    {
        return resolve(self::class);
    }

    /**
     * Instantiates a HealthStatus with status WARNING
     *
     * @return HealthStatus
     */
    public static function warning(): HealthStatus
    {
        $status = resolve(self::class);
        $status->setStatusWarning();

        return $status;
    }

    /**
     * Sets status to WARNING
     */
    public function setStatusWarning(): HealthStatus
    {
        $this->statusCode = self::WARNING;

        return $this;
    }

    /**
     * Instantiates a HealthStatus with status FAIL
     *
     * @return HealthStatus
     */
    public static function fail(): HealthStatus
    {
        $status = resolve(self::class);
        $status->setStatusFail();

        return $status;
    }

    /**
     * Sets status to FAIL
     */
    public function setStatusFail(): HealthStatus
    {
        $this->statusCode = self::FAIL;

        return $this;
    }

    /**
     * Sets the status message
     *
     * @param string $message
     */
    public function setMessage(string $message): HealthStatus
    {
        $this->statusMessage = $message;

        return $this;
    }

    /**
     * Sets the status to a new value, if the status is worse than the current one
     *
     * @param HealthStatus $status
     *
     * @return HealthStatus
     */
    public function setStatusIfWorse(HealthStatus $status): HealthStatus
    {
        $this->statusCode = max($this->statusCode, $status->getStatusCode());

        return $this;
    }

    /**
     * Gets the status text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->statusText[$this->statusCode];
    }

    /**
     * Gets the status message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->statusMessage;
    }
}
