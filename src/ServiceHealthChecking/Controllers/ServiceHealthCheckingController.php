<?php

namespace Cego\ServiceHealthChecking\Controllers;

use Illuminate\Http\JsonResponse;
use Cego\ServiceHealthChecking\HealthCheckingUtils;
use Illuminate\Support\Facades\Config;

class ServiceHealthCheckingController extends Controller
{
    /**
     * Endpoint for checking the health of a service
     */
    public function index(): JsonResponse
    {
        $healthResponse = HealthCheckingUtils::performChecks(Config::get('service-health-checking.registry') ?? []);

        return response()->json($healthResponse->toArray());
    }
}
