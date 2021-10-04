<?php

namespace Cego\ServiceHealthChecking\Controllers;

use Cego\ServiceHealthChecking\Interfaces\HealthCheck;

class ServiceHealthCheckingController extends Controller
{
    /**
     * Endpoint for checking the health of a service
     */
    public function index()
    {
        $errors = $this->performChecks(config('service-health-checking.registry') ?? []);

        if ($errors) {
            return response()->json(['errors' => $errors], 500);
        }

        return response()->json();
    }

    /**
     * Performs health checks
     */
    protected function performChecks(array $healthCheckClasses): array
    {
        $errors = [];

        foreach ($healthCheckClasses as $healthCheckClass) {
            /** @var HealthCheck $healthCheck */
            $healthCheck = resolve($healthCheckClass);

            // Ensure that we implement the HealthCheck interface
            if ( ! $healthCheck instanceof HealthCheck) {
                $errors[] = sprintf('Class %s must implement %s', get_class($healthCheck), HealthCheck::class);

                continue;
            }

            // Perform check and register errors if any
            if ( ! $healthCheck->check()) {
                $errors[] = $healthCheck->getErrorMessage();
            }
        }

        return $errors;
    }
}
