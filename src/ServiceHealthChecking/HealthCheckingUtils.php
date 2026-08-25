<?php

namespace Cego\ServiceHealthChecking;

use ReflectionClass;
use Illuminate\Contracts\Container\BindingResolutionException;

class HealthCheckingUtils
{
    /**
     * Performs health checks
     *
     * @param string[] $healthCheckClasses
     *
     * @return HealthResponse
     */
    public static function performChecks(array $healthCheckClasses): HealthResponse
    {
        $response = new HealthResponse();

        foreach ($healthCheckClasses as $healthCheckClass) {
            try {
                $healthCheck = resolve($healthCheckClass);
            } catch (BindingResolutionException $exception) {
                // Report a registry entry that cannot be resolved (e.g. a removed class) as a failing check instead of crashing the endpoint
                $healthCheckResponse = new HealthCheckResponse(
                    HealthStatus::fail()->setMessage("Registered health check class {$healthCheckClass} could not be resolved: {$exception->getMessage()}"),
                    class_basename($healthCheckClass),
                    ''
                );

                $response->addHealthCheckResponse($healthCheckResponse);

                continue;
            }

            // Ensure that we extend BaseHealthCheck
            if ( ! $healthCheck instanceof BaseHealthCheck) {
                $healthCheckResponse = new HealthCheckResponse(
                    HealthStatus::fail()->setMessage(sprintf('Class %s must extend %s', get_class($healthCheck), BaseHealthCheck::class)),
                    (new ReflectionClass($healthCheck))->getShortName(),
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
