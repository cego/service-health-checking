<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckFail;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckPass;
use Cego\ServiceHealthChecking\Controllers\ServiceHealthCheckingController;

class ServiceHealthCheckingControllerTest extends TestCase
{
    /** @test */
    public function performChecks_returns_empty_checks_array_if_no_checks()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthCheckingController::class, 'performChecks');

        // Act
        $healthResponse = $method->invokeArgs(resolve(ServiceHealthCheckingController::class), [[]]);

        // Assert
        $this->assertEquals(['status' => 'pass', 'checks' => []], $healthResponse->toArray());
    }

    /** @test */
    public function performChecks_returns_correct_response_if_checks_passes()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthCheckingController::class, 'performChecks');
        $checks = [
            TestHealthCheckPass::class,
        ];

        // Act
        $healthResponse = $method->invokeArgs(resolve(ServiceHealthCheckingController::class), [$checks]);

        // Assert
        $this->assertEquals([
            'status' => 'pass',
            'checks' => [
                [
                    'status'      => 'pass',
                    'name'        => 'TestHealthCheckPass',
                    'description' => 'This is a test health check that PASSES',
                    'message'     => '',
                ],
            ],
        ], $healthResponse->toArray());
    }

    /** @test */
    public function performChecks_returns_array_with_errors_if_checks_fail()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthCheckingController::class, 'performChecks');
        $checks = [
            TestHealthCheckFail::class,
        ];

        // Act
        $healthResponse = $method->invokeArgs(resolve(ServiceHealthCheckingController::class), [$checks]);

        // Assert
        $this->assertEquals([
            'status' => 'fail',
            'checks' => [
                [
                    'status'      => 'fail',
                    'name'        => 'TestHealthCheckFail',
                    'description' => 'This is a test health check that FAILS',
                    'message'     => 'It failed',
                ],
            ],
        ], $healthResponse->toArray());
    }
}
