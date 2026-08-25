<?php

return [
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class,
        // Opt-in: verifies cache read/write. Redundant when the cache driver is 'database'.
        // \Cego\ServiceHealthChecking\CacheCheck::class,
    ],
];
