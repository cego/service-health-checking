<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\Tests\TestCase;

class HealthStatusTest extends TestCase
{
    /** @test */
    public function it_can_instantiates_pass()
    {
        // Arrange / act
        $status = HealthStatus::pass();

        // Assert
        $this->assertEquals('pass', $status->getText());
    }

    /** @test */
    public function it_can_instantiates_warning()
    {
        // Arrange / act
        $status = HealthStatus::warning();

        // Assert
        $this->assertEquals('warning', $status->getText());
    }

    /** @test */
    public function it_can_instantiates_fail()
    {
        // Arrange / act
        $status = HealthStatus::fail();

        // Assert
        $this->assertEquals('fail', $status->getText());
    }

    /** @test */
    public function it_upshifts_status()
    {
        $this->assertEquals('warning', HealthStatus::pass()->setStatusIfWorse(HealthStatus::warning())->getText());
        $this->assertEquals('fail', HealthStatus::pass()->setStatusIfWorse(HealthStatus::fail())->getText());
        $this->assertEquals('fail', HealthStatus::warning()->setStatusIfWorse(HealthStatus::fail())->getText());
    }

    /** @test */
    public function it_doesnt_downshift_status()
    {
        $this->assertEquals('fail', HealthStatus::fail()->setStatusIfWorse(HealthStatus::pass())->getText());
        $this->assertEquals('fail', HealthStatus::fail()->setStatusIfWorse(HealthStatus::warning())->getText());
        $this->assertEquals('warning', HealthStatus::warning()->setStatusIfWorse(HealthStatus::pass())->getText());
    }
}
