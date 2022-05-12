<?php

namespace Cego\ServiceHealthChecking\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckFail;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckPass;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckSkip;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckWarn;

class ServiceHealthCheckingEndpointTest extends TestCase
{
    /** @test */
    public function it_returns_correct_response_on_success()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ TestHealthCheckPass::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'pass',
            'checks' => [
                [
                    'status'      => 'pass',
                    'name'        => 'TestHealthCheckPass',
                    'description' => 'This is a test health check that PASSES',
                    'message'     => '',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_returns_correct_response_on_warn()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ TestHealthCheckWarn::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'warn',
            'checks' => [
                [
                    'status'      => 'warn',
                    'name'        => 'TestHealthCheckWarn',
                    'description' => 'This is a test health check that WARNS',
                    'message'     => 'It warns',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_returns_correct_response_on_failure()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ TestHealthCheckFail::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'fail',
            'checks' => [
                [
                    'status'      => 'fail',
                    'name'        => 'TestHealthCheckFail',
                    'description' => 'This is a test health check that FAILS',
                    'message'     => 'It failed',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_skips()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ TestHealthCheckSkip::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'pass',
            'checks' => [],
        ]);
    }
}
