<?php

return [
    // Register health check classes here. They must implement Cego\ServiceHealth\Interfaces\HealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class
    ]
];
