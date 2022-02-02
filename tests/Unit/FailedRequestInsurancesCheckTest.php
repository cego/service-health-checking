<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthStatusCode;
use Cego\ServiceHealthChecking\FailedRequestInsurancesCheck;
use Illuminate\Support\Facades\Config;

class FailedRequestInsurancesCheckTest extends TestCase
{
    private FailedRequestInsurancesCheck $mock;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('service-health-checking.request-insurance.failed-thresholds.warn', 10);
        Config::set('service-health-checking.request-insurance.failed-thresholds.fail', 50);

        $this->mock = $this->createPartialMock(FailedRequestInsurancesCheck::class, ['getCount']);
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
    }

    /** @test */
    public function it_warns()
    {
        // Arrange
        $this->mock->expects($this->once())->method('getCount')->willReturn(10);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::WARN, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_fails()
    {
        // Arrange
        $this->mock->expects($this->once())->method('getCount')->willReturn(50);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::FAIL, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_disables_warn()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.warn', 0);
        $this->mock->expects($this->once())->method('getCount')->willReturn(10);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::PASS, $response->getStatus()->getStatusCode());
    }

    /** @test */
    public function it_disables_fail()
    {
        // Arrange
        Config::set('service-health-checking.request-insurance.failed-thresholds.fail', 0);
        $this->mock->expects($this->once())->method('getCount')->willReturn(50);

        // Act
        $response = $this->mock->getResponse();

        // Assert
        $this->assertEquals(HealthStatusCode::WARN, $response->getStatus()->getStatusCode());
    }
}
