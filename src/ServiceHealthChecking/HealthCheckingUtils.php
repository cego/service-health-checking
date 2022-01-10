<?php

namespace Cego\ServiceHealthChecking;

class HealthCheckingUtils
{
    /**
     * Performs health checks
     */
    public static function performChecks(array $healthCheckClasses): HealthResponse
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

            // Get the health check response
            $response->addHealthCheckResponse($healthCheck->getResponse());
        }

        return $response;
    }
}
