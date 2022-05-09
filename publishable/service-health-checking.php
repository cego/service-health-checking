<?php

return [
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\ServiceHealthConfigCheck::class,
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class,
        \Cego\ServiceHealthChecking\CacheCheck::class,
        \Cego\ServiceHealthChecking\FailedRequestInsurancesCheck::class,
        \Cego\ServiceHealthChecking\ActiveRequestInsurancesCheck::class,
    ],
    // Define the thresholds for request insurance health levels
    'request-insurance' => [
        // If true, RI checks will be performed only if the package is installed
        'perform-check' => true,
        // When active RIs cross these thresholds, the check will warn or fail. 0 = disable.
        'active-thresholds' => [
            'warn' => 50,
            'fail' => 1000,
        ],
        // When failed RIs cross these thresholds, the check will warn or fail. 0 = disable.
        'failed-thresholds' => [
            'warn' => 5,
            'fail' => 50,
        ],
    ],
];
