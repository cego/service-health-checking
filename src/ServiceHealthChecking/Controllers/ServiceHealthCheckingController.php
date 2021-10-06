<?php

namespace Cego\ServiceHealthChecking\Controllers;

use Illuminate\Http\JsonResponse;
use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\HealthResponse;
use Cego\ServiceHealthChecking\BaseHealthCheck;
use Cego\ServiceHealthChecking\HealthCheckResponse;

class ServiceHealthCheckingController extends Controller
{
    /**
     * Endpoint for checking the health of a service
     */
    public function index(): JsonResponse
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
    private function performChecks(array $healthCheckClasses): HealthResponse
    {
        $response = new HealthResponse();

        foreach ($healthCheckClasses as $healthCheckClass) {
            /** @var BaseHealthCheck $healthCheck */
            $healthCheck = resolve($healthCheckClass);

            // Ensure that we extend BaseHealthCheck
            if ( ! $healthCheck instanceof BaseHealthCheck) {
                $healthCheckResponse = new HealthCheckResponse(
                    HealthStatus::fail(),
                    (new \ReflectionClass($healthCheck))->getShortName(),
                    '',
                    sprintf('Class %s must extend %s', get_class($healthCheck), BaseHealthCheck::class)
                );

                $response->addHealthCheckResponse($healthCheckResponse);

                continue;
            }

            // Get the health check response
            $response->addHealthCheckResponse($healthCheck->getResponse());
        }

        return $response;
    }
}
