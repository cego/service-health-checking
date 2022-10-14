<?php
namespace Cego\ServiceHealthChecking;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CacheCheck extends BaseHealthCheck
{
    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks read/write access to the cache';
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        try {
            $key = 'CACHE_CHECK.' . Str::random();

            Cache::put($key, true);

            return Cache::pull($key, false) === true
                ? HealthStatus::pass()
                : HealthStatus::fail()->setMessage('Could not read from cache');
        } catch (Exception $exception) {
            return HealthStatus::fail()->setMessage($exception->getMessage());
        }
    }
}
