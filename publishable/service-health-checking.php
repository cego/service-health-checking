<?php

return [
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class,
        // Opt-in: checks read/write access to the cache.
        // \Cego\ServiceHealthChecking\CacheCheck::class,
    ],
];
