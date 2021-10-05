<?php
namespace Cego\ServiceHealthChecking;

use Exception;
use Illuminate\Support\Facades\DB;

class DefaultDatabaseConnectionCheck extends BaseHealthCheck
{
    /**
     * @inheritdoc
     */
    protected string $description = 'Checks if it is possible to connect to the default database connection';

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
}
