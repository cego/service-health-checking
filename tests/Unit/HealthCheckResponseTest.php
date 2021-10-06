<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthCheckResponse;

class HealthCheckResponseTest extends TestCase
{
    /** @test */
    public function it_converts_to_array()
    {
        // Arrange
        $healthCheckResponse = new HealthCheckResponse(
            HealthStatus::fail()->setMessage('Test message'),
            'TestHealthCheck',
            'Test health check description',
        );

        // Act
        $array = $healthCheckResponse->toArray();

        // Assert
        $this->assertEquals([
            'status'      => 'fail',
            'name'        => 'TestHealthCheck',
            'description' => 'Test health check description',
            'message'     => 'Test message'
        ], $array);
    }
}
