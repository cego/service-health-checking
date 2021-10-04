<?php

namespace Cego\ServiceHealthChecking\Tests;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use Cego\ServiceHealthChecking\ServiceHealthCheckingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceHealthCheckingServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * getPrivateMethod
     *
     * @param string $className
     * @param string $methodName
     *
     * @throws ReflectionException
     *
     * @return    ReflectionMethod
     */
    protected function getPrivateMethod($className, $methodName): ReflectionMethod
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
