<?php

return [
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class,
        \Cego\ServiceHealthChecking\CacheCheck::class,
    ],
    // Define the thresholds for request insurance health levels
    'request-insurance' => [
        'perform-check'     => true,    // If true, RI checks will be performed only if the package is installed
        'active-thresholds' => [
            'warn' => 50,
            'fail' => 100,
        ],
        'failed-thresholds' => [
            'warn' => 1,
            'fail' => 10,
        ],
    ],
];
