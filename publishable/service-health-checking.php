<?php

return [
    // Middleware applied to the health endpoints. Empty by default — they are polled by
    // monitoring, not a browser, so they carry no session and no authorization gate.
    'middleware' => [],
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class,
        // Opt-in: checks read/write access to the cache.
        // \Cego\ServiceHealthChecking\CacheCheck::class,
    ],
];
