<?php

namespace Cego\ServiceHealthChecking\Controllers;

use Illuminate\Http\JsonResponse;
use Cego\ServiceHealthChecking\HealthCheckingUtils;

class ServiceHealthCheckingController extends Controller
{
    /**
     * Endpoint for checking the health of a service
     */
    public function index(): JsonResponse
    {
        $healthResponse = HealthCheckingUtils::performChecks(config('service-health-checking.registry') ?? []);

        return response()->json($healthResponse->toArray());
    }
}
