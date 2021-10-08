<?php
namespace Cego\ServiceHealthChecking;

use Exception;
use Illuminate\Support\Facades\Cache;

class CacheCheck extends BaseHealthCheck
{
    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks if it is write to cache';
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        try {
            Cache::put('CACHE_CHECK', true);

            return Cache::pull('CACHE_CHECK', false) === true
                ? HealthStatus::pass()
                : HealthStatus::fail()->setMessage('Could not read from cache');
        } catch (Exception $exception) {
            return HealthStatus::fail()->setMessage($exception->getMessage());
        }
    }
}
