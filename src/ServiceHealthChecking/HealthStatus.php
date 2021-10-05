<?php

namespace Cego\ServiceHealthChecking;

use Exception;

final class HealthStatus
{
    const PASS = 0;
    const WARNING = 1;
    const FAIL = 2;

    /**
     * The current status
     *
     * @var int
     */
    private int $status = self::PASS;

    /**
     * Contains the supported statuses
     *
     * @var array
     */
    private array $supportedStatuses = [
        self::PASS,
        self::WARNING,
        self::FAIL
    ];

    /**
     * @return HealthStatus
     */
    public static function pass(): HealthStatus
    {
        return new self();
    }

    /**
     * @return HealthStatus
     *
     * @throws Exception
     */
    public static function warn(): HealthStatus
    {
        $status = new self();
        $status->setStatusIfWorse(self::WARNING);
        return $status;
    }

    /**
     * @return HealthStatus
     *
     * @throws Exception
     */
    public static function fail(): HealthStatus
    {
        $status = new self();
        $status->setStatusIfWorse(self::FAIL);
        return $status;
    }

    /**
     * Sets the status to a new value, if the status is worse than the current one
     *
     * @throws Exception
     */
    public function setStatusIfWorse(int $status)
    {
        if(!in_array($status, $this->supportedStatuses)) {
            throw new Exception(sprintf('%s: Tried to trigger invalid status "%s"', __METHOD__, $status));
        }

        $this->status = max($this->status, $status);
    }

    /**
     * Get the status text
     *
     * @return string
     * @throws Exception
     */
    private function getStatusText(): string
    {
        switch ($this->status) {
            case self::PASS;
                return 'pass';
            case self::WARNING:
                return 'warn';
            case self::FAIL:
                return 'fail';
        }

        throw new Exception(sprintf('%s: Tried to get text for invalid status "%s"', __METHOD__, $this->status));
    }

    /**
     * @throws Exception
     */
    public function __toString()
    {
        return $this->getStatusText();
    }

}
