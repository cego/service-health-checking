<?php

namespace Cego\ServiceHealthChecking\Tests\Feature;

use Illuminate\Support\Facades\Gate;
use Cego\ServiceHealthChecking\Tests\TestCase;

class ConfiguredRouteMiddlewareTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('service-health-checking.middleware', ['can:poll-health']);
    }

    public function test_the_application_can_restrict_who_may_poll()
    {
        Gate::define('poll-health', fn () => false);

        $this->getJson(route('vendor.service-health-checking.index'))->assertForbidden();
        $this->getJson(route('vendor.service-health-checking.ping'))->assertForbidden();
    }
}
