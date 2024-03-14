<?php

namespace Cego\ServiceHealthChecking\Tests\Unit;

use Cego\ServiceHealthChecking\HealthStatus;
use Cego\ServiceHealthChecking\Tests\TestCase;

class HealthStatusTest extends TestCase
{
    public function test_it_can_instantiates_pass()
    {
        // Arrange / act
        $status = HealthStatus::pass();

        // Assert
        $this->assertEquals('pass', $status->getText());
    }

    public function test_it_can_instantiates_warn()
    {
        // Arrange / act
        $status = HealthStatus::warn();

        // Assert
        $this->assertEquals('warn', $status->getText());
    }

    public function test_it_can_instantiates_fail()
    {
        // Arrange / act
        $status = HealthStatus::fail();

        // Assert
        $this->assertEquals('fail', $status->getText());
    }

    public function test_it_upshifts_status()
    {
        $this->assertEquals('warn', HealthStatus::pass()->setStatusIfWorse(HealthStatus::warn())->getText());
        $this->assertEquals('fail', HealthStatus::pass()->setStatusIfWorse(HealthStatus::fail())->getText());
        $this->assertEquals('fail', HealthStatus::warn()->setStatusIfWorse(HealthStatus::fail())->getText());
    }

    public function test_it_doesnt_downshift_status()
    {
        $this->assertEquals('fail', HealthStatus::fail()->setStatusIfWorse(HealthStatus::pass())->getText());
        $this->assertEquals('fail', HealthStatus::fail()->setStatusIfWorse(HealthStatus::warn())->getText());
        $this->assertEquals('warn', HealthStatus::warn()->setStatusIfWorse(HealthStatus::pass())->getText());
    }
}
