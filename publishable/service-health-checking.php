<?php

return [
    // Register health check classes here. They must extend Cego\ServiceHealthChecking\BaseHealthCheck.
    'registry' => [
        \Cego\ServiceHealthChecking\DefaultDatabaseConnectionCheck::class
    ]
];
