<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Mockery\MockInterface;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\Tests\TestHealthCheck;
use Cego\ServiceHealthChecking\Controllers\ServiceHealthController;

class ServiceHealthControllerTest extends TestCase
{
    /** @test */
    public function performChecks_returns_empty_array_if_no_checks_are_defined()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthController::class, 'performChecks');

        // Act
        $errors = $method->invokeArgs(resolve(ServiceHealthController::class), [[]]);

        // Assert
        $this->assertEquals([], $errors);
    }

    /** @test */
    public function performChecks_returns_empty_array_if_checks_passes()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthController::class, 'performChecks');
        $checks = [
            TestHealthCheck::class
        ];

        // Expect
        $this->mock(TestHealthCheck::class, function (MockInterface $mock) {
            $mock->expects('check')->once()->andReturn(true);
        });

        // Act
        $errors = $method->invokeArgs(resolve(ServiceHealthController::class), [$checks]);

        // Assert
        $this->assertEquals([], $errors);
    }

    /** @test */
    public function performChecks_returns_array_with_errors_if_checks_fail()
    {
        // Arrange
        $method = $this->getPrivateMethod(ServiceHealthController::class, 'performChecks');
        $checks = [
            TestHealthCheck::class
        ];

        // Expect
        $this->mock(TestHealthCheck::class, function (MockInterface $mock) {
            $mock->expects('check')->once()->andReturn(false);
            $mock->expects('getErrorMessage')->once()->andReturn('The test health check failed');
        });

        // Act
        $errors = $method->invokeArgs(resolve(ServiceHealthController::class), [$checks]);

        // Assert
        $this->assertEquals([ 'The test health check failed' ], $errors);
    }
}
