<?php

namespace Cego\ServiceHealthChecking\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\BaseHealthCheck;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckFail;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckPass;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckSkip;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckWarn;
use Cego\ServiceHealthChecking\Tests\TestHealthCheckException;

class ServiceHealthCheckingEndpointTest extends TestCase
{
    public function test_it_returns_correct_response_on_success()
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

    public function test_it_returns_correct_response_on_warn()
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

    public function test_it_returns_correct_response_on_failure()
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

    public function test_it_skips()
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

    public function test_it_handles_exception()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ TestHealthCheckException::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'fail',
            'checks' => [
                [
                    'status'      => 'fail',
                    'name'        => 'TestHealthCheckException',
                    'description' => 'This is a test health check that throws an exception',
                ],
            ],
        ]);

        $this->assertStringStartsWith('Exception: Oh no...', json_decode($response->getContent(), true)['checks'][0]['message']);
    }

    public function test_it_runs_the_default_registry()
    {
        // Arrange - no registry override, so the package default from publishable/service-health-checking.php applies

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'pass',
            'checks' => [
                [
                    'status'      => 'pass',
                    'name'        => 'DefaultDatabaseConnectionCheck',
                    'description' => 'Checks if it is possible to connect to the default database',
                    'message'     => '',
                ],
            ],
        ]);
    }

    public function test_it_reports_missing_health_check_class_as_failure()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ 'Cego\ServiceHealthChecking\RemovedHealthCheck' ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'fail',
            'checks' => [
                [
                    'status'      => 'fail',
                    'name'        => 'RemovedHealthCheck',
                    'description' => '',
                ],
            ],
        ]);

        $this->assertStringStartsWith(
            'Registered health check class Cego\ServiceHealthChecking\RemovedHealthCheck could not be resolved: ',
            json_decode($response->getContent(), true)['checks'][0]['message']
        );
    }

    public function test_it_reports_non_instantiable_health_check_class_as_failure()
    {
        // Arrange
        Config::set('service-health-checking.registry', [ BaseHealthCheck::class ]);

        // Act
        $response = $this->getJson(route('vendor.service-health-checking.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'fail',
            'checks' => [
                [
                    'status'      => 'fail',
                    'name'        => 'BaseHealthCheck',
                    'description' => '',
                ],
            ],
        ]);

        $this->assertStringStartsWith(
            'Registered health check class Cego\ServiceHealthChecking\BaseHealthCheck could not be resolved: ',
            json_decode($response->getContent(), true)['checks'][0]['message']
        );
    }
}
