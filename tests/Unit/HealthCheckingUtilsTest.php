<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthCheckingUtils;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckFail;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckPass;

class HealthCheckingUtilsTest extends TestCase
{
    public function test_performChecks_returns_empty_checks_array_if_no_checks()
    {
        // Act
        $healthResponse = HealthCheckingUtils::performChecks([]);

        // Assert
        $this->assertEquals(['status' => 'pass', 'checks' => []], $healthResponse->toArray());
    }

    public function test_performChecks_returns_correct_response_if_checks_passes()
    {
        // Arrange
        $checks = [
            TestHealthCheckPass::class,
        ];

        // Act
        $healthResponse = HealthCheckingUtils::performChecks($checks);

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

    public function test_performChecks_returns_array_with_errors_if_checks_fail()
    {
        // Arrange
        $checks = [
            TestHealthCheckFail::class,
        ];

        // Act
        $healthResponse = HealthCheckingUtils::performChecks($checks);

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
