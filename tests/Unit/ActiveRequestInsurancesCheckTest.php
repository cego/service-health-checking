<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthStatusCode;
use Cego\ServiceHealthChecking\ActiveRequestInsurancesCheck;

class ActiveRequestInsurancesCheckTest extends TestCase
{
    private ActiveRequestInsurancesCheck $mock;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('service-health-checking.request-insurance.active-thresholds.warn', 50);
        Config::set('service-health-checking.request-insurance.active-thresholds.fail', 100);

        $this->mock = $this->createPartialMock(ActiveRequestInsurancesCheck::class, ['getCount']);
    }

    /** @test */
    public function it_passes()
    {
        // Arrange
        $this->mock->expects($this->once())->method('getCount')->willReturn(0);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::PASS, $response->getStatus()->getStatusCode());
        $this->assertEquals('Active Request Insurances count: 0. Warn threshold: 50, fail threshold: 100.', $response->getStatus()->getMessage());
    }

    /** @test */
    public function it_warns()
    {
        // Arrange
        $this->mock->expects($this->once())->method('getCount')->willReturn(50);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::WARN, $response->getStatus()->getStatusCode());
        $this->assertEquals('Active Request Insurances count: 50. Warn threshold: 50, fail threshold: 100.', $response->getStatus()->getMessage());
    }

    /** @test */
    public function it_fails()
    {
        // Arrange
        $this->mock->expects($this->once())->method('getCount')->willReturn(100);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
        $this->assertEquals('Active Request Insurances count: 100. Warn threshold: 50, fail threshold: 100.', $response->getStatus()->getMessage());
    }

    /** @test */
    public function it_disables_warn()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.warn', 0);
        $this->mock->expects($this->once())->method('getCount')->willReturn(50);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::PASS, $response->getStatus()->getStatusCode());
        $this->assertEquals('Active Request Insurances count: 50. Warn threshold: disabled, fail threshold: 100.', $response->getStatus()->getMessage());
    }

    /** @test */
    public function it_disables_fail()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.active-thresholds.fail', 0);
        $this->mock->expects($this->once())->method('getCount')->willReturn(100);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::WARN, $response->getStatus()->getStatusCode());
        $this->assertEquals('Active Request Insurances count: 100. Warn threshold: 50, fail threshold: disabled.', $response->getStatus()->getMessage());
    }
}
