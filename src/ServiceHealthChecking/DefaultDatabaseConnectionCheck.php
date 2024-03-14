<?php

namespace Cego\ServiceHealthChecking;

use Exception;
use Illuminate\Support\Facades\DB;

class DefaultDatabaseConnectionCheck extends BaseHealthCheck
{
    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks if it is possible to connect to the default database';
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        try {
            DB::connection()->getPdo();

            return HealthStatus::pass();
        } catch (Exception $exception) {
            return HealthStatus::fail()->setMessage($exception->getMessage());
        }
    }
}
