<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthStatusCode;
use Cego\ServiceHealthChecking\ServiceHealthConfigCheck;
use Illuminate\Support\Facades\Config;

class ServiceHealthConfigCheckTest extends TestCase
{
    private ServiceHealthConfigCheck $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $config = include __DIR__ . '/../../publishable/service-health-checking.php';
        Config::set('service-health-checking', $config);
        $mock = $this->createPartialMock(ServiceHealthConfigCheck::class, ['shouldPerformRequestInsuranceConfigCheck']);
        $mock->expects($this->once())->method('shouldPerformRequestInsuranceConfigCheck')->willReturn(true);
        $this->mock = $mock;
    }

    /** @test */
    public function it_accepts_default_config()
    {
        // Arrange

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::PASS, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_perform_check_is_not_boolean()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.perform-check', 'hest');

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_active_threshold_warn_is_not_integer()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.warn', 'hest');

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_active_threshold_fail_is_not_integer()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.fail', 'hest');

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_failed_threshold_warn_is_not_integer()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.warn', 'hest');

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_failed_threshold_fail_is_not_integer()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.fail', 'hest');

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_active_threshold_warn_is_negative()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.warn', -1);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_active_threshold_fail_is_not_negative()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.fail', -1);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_failed_threshold_warn_is_not_negative()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.warn', -1);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_failed_threshold_fail_is_not_negative()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.fail', -1);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_active_threshold_warn_is_greater_than_active_threshold_fail()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.fail', 5);
        Config::set('service-health-checking.request-insurance.active-thresholds.warn', 10);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails_when_failed_threshold_warn_is_greater_than_failed_threshold_fail()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.fail', 5);
        Config::set('service-health-checking.request-insurance.failed-thresholds.warn', 10);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }
}
