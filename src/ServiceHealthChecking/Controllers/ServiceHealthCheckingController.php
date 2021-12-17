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
        $healthResponse = $this->performChecks(config('service-health-checking.registry') ?? []);

        return response()->json($healthResponse->toArray());
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
                    HealthStatus::fail()->setMessage(sprintf('Class %s must extend %s', get_class($healthCheck), BaseHealthCheck::class)),
                    (new \ReflectionClass($healthCheck))->getShortName(),
                    ''
                );

                $response->addHealthCheckResponse($healthCheckResponse);

                continue;
            }

            if ( ! $healthCheck->shouldSkip()) {
                // Get the health check response
                $response->addHealthCheckResponse($healthCheck->getResponse());
            }
        }

        return $response;
    }
}
