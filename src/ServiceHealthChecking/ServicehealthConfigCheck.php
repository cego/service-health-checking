<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;

class ServicehealthConfigCheck extends BaseHealthCheck
{
    private array $errorMessages = [];
    private bool $configIsOk = true;

    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks that the service health config is ok';
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        $this->checkBaseConfig();
        $this->checkRequestInsuranceConfig();

        if ( ! $this->configIsOk) {
            return HealthStatus::fail()->setMessage(sprintf('%s.', implode('. ', $this->errorMessages)));
        }

        return HealthStatus::pass();
    }

    /**
     * Checks the base config of Service Health Checking package
     *
     * @return void
     */
    private function checkBaseConfig()
    {
        if ( ! is_array(config('service-health-checking.registry'))) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'registry must be an array';
        }
    }

    /**
     * Checks the request insurance config of Service Health Checking package
     *
     * @return void
     */
    private function checkRequestInsuranceConfig()
    {
        if ( ! InstalledVersions::isInstalled('cego/request-insurance') || config('service-health-checking.request-insurance.perform-check') === false) {
            return;
        }

        if ( ! is_bool(config('service-health-checking.request-insurance.perform-check'))) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'perform-check must be boolean';
        }

        $keysThatMustBePositiveIntegers = [
            'active-thresholds.warn',
            'active-thresholds.fail',
            'failed-thresholds.warn',
            'failed-thresholds.fail',
        ];

        foreach ($keysThatMustBePositiveIntegers as $key) {
            $fullKey = sprintf('service-health-checking.request-insurance.%s', $key);
            $value = config($fullKey);

            if ( ! is_int($value) || $value < 0) {
                $this->configIsOk = false;
                $this->errorMessages[] = sprintf('%s must be an integer greater than 0', $key);
            }
        }

        if (config('service-health-checking.request-insurance.active-thresholds.warn') > config('service-health-checking.request-insurance.active-thresholds.warn')) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'active-thresholds.warn must not be greater than active-thresholds.fail';
        }

        if (config('service-health-checking.request-insurance.failed-thresholds.warn') > config('service-health-checking.request-insurance.failed-thresholds.warn')) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'failed-thresholds.warn must not be greater than failed-thresholds.fail';
        }
    }
}
