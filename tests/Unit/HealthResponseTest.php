<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\HealthResponse;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthCheckResponse;

class HealthResponseTest extends TestCase
{
    /** @test */
    public function it_converts_to_json()
    {
        // Arrange
        $healthResponse = new HealthResponse();

        $healthCheckResponse = new HealthCheckResponse(
            HealthStatus::pass()->setMessage('Test message'),
            'TestHealthCheck',
            'Test health check description',
        );

        $healthResponse->addHealthCheckResponse($healthCheckResponse);

        // Act
        $array = $healthResponse->toArray();

        // Assert
        $this->assertEquals([
            'status' => 'pass',
            'checks' => [
                 [
                    'status'      => 'pass',
                    'name'        => 'TestHealthCheck',
                    'description' => 'Test health check description',
                    'message'     => 'Test message',
                ],
            ],
        ], $array);
    }

    /** @test */
    public function the_status_matches_the_check_statuses()
    {
        $healthResponse = new HealthResponse();
        $this->assertEquals('pass', $healthResponse->getStatus()->getText());

        $healthCheckResponse = new HealthCheckResponse(HealthStatus::warn(), '', '', '');
        $healthResponse->addHealthCheckResponse($healthCheckResponse);
        $this->assertEquals('warn', $healthResponse->getStatus()->getText());

        $healthCheckResponse = new HealthCheckResponse(HealthStatus::fail(), '', '', '');
        $healthResponse->addHealthCheckResponse($healthCheckResponse);
        $this->assertEquals('fail', $healthResponse->getStatus()->getText());

        $healthCheckResponse = new HealthCheckResponse(HealthStatus::warn(), '', '', '');
        $healthResponse->addHealthCheckResponse($healthCheckResponse);
        $this->assertEquals('fail', $healthResponse->getStatus()->getText());

        $healthCheckResponse = new HealthCheckResponse(HealthStatus::pass(), '', '', '');
        $healthResponse->addHealthCheckResponse($healthCheckResponse);
        $this->assertEquals('fail', $healthResponse->getStatus()->getText());
    }
}
