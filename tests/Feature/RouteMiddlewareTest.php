<?php

namespace Cego\ServiceHealthChecking\Tests\Feature;

use Illuminate\Routing\Route;
use Cego\ServiceHealthChecking\Tests\TestCase;
use Illuminate\Support\Facades\Route as RouteFacade;

class RouteMiddlewareTest extends TestCase
{
    public function test_middleware_defaults_to_empty()
    {
        $this->assertSame([], config('service-health-checking.middleware'));
    }

    public function test_every_package_route_applies_the_configured_middleware()
    {
        $routes = $this->packageRoutes();

        $this->assertCount(2, $routes);

        foreach ($routes as $route) {
            $this->assertSame([], $route->gatherMiddleware(), sprintf('Route %s has unexpected middleware', $route->uri()));
        }
    }

    /**
     * @return array<int, Route>
     */
    private function packageRoutes(): array
    {
        return array_values(array_filter(
            RouteFacade::getRoutes()->getRoutes(),
            fn (Route $route) => strpos($route->uri(), 'vendor/service-health-checking') === 0
        ));
    }
}
