<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\Tests\TestCase;
use Cego\ServiceHealthChecking\HealthStatusCode;
use Cego\ServiceHealthChecking\ActiveRequestInsurancesCheck;

class ActiveRequestInsurancesCheckTest extends TestCase
{
    private ActiveRequestInsurancesCheck $mock;

    protected function setUp(): void
    {
        parent::setUp();

        config(['service-health-checking.request-insurance.active-thresholds.warn' => 50]);
        config(['service-health-checking.request-insurance.active-thresholds.fail' => 100]);

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
    }
}
