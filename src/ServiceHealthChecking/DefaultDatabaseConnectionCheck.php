<?php
namespace Cego\ServiceHealthChecking;

use Illuminate\Support\Facades\DB;
use Cego\ServiceHealthChecking\Interfaces\HealthCheck;

class DefaultDatabaseConnectionCheck implements HealthCheck
{
    /**
     * @inheritDoc
     */
    public function check(): bool
    {
        try {
            DB::connection()->getPdo();
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string
    {
        return 'Failed to connect to default database';
    }
}
