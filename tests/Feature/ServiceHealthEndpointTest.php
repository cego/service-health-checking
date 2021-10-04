<?php

namespace Cego\ServiceHealthChecking\Tests\Feature;

use Mockery\MockInterface;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\Tests\TestHealthCheck;

class ServiceHealthEndpointTest extends TestCase
{
    /** @test */
    public function it_returns_200_on_success()
    {
        // Arrange
        config(['service-health-checking.registry' => [ TestHealthCheck::class ] ]);

        // Expect
        $this->mock(TestHealthCheck::class, function (MockInterface $mock) {
            return $mock->expects('check')->once()->andReturn(true);
        });

        // Act
        $response = $this->getJson(route('vendor.service-health.index'));

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_500_on_failure()
    {
        // Arrange
        config(['service-health-checking.registry' => [ TestHealthCheck::class ] ]);

        // Expect
        $this->mock(TestHealthCheck::class, function (MockInterface $mock) {
            $mock->expects('check')->once()->andReturn(false);
            $mock->expects('getErrorMessage')->once()->andReturn('The test health check failed');
        });

        // Act
        $response = $this->getJson(route('vendor.service-health.index'));

        // Assert
        $response->assertStatus(500);
        $response->assertJsonPath('errors.0', "The test health check failed");
    }
}
