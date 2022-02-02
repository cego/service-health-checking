<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;

class ServiceHealthConfigCheck extends BaseHealthCheck
{
    const TYPE_FAILED = 'failed';
    const TYPE_ACTIVE = 'active';

    const SEVERITY_WARN = 'warn';
    const SEVERITY_FAIL = 'fail';

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
        if ( ! is_array(Config::get('service-health-checking.registry'))) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'registry must be an array';
        }
    }

    /**
     * Determines whether Request Insurance config check should be performed
     *
     * @return bool
     */
    protected function shouldPerformRequestInsuranceConfigCheck(): bool
    {
        return InstalledVersions::isInstalled('cego/request-insurance') && Config::get('service-health-checking.request-insurance.perform-check');
    }

    /**
     * Checks the request insurance config of Service Health Checking package
     *
     * @return void
     */
    private function checkRequestInsuranceConfig()
    {
        if ( ! $this->shouldPerformRequestInsuranceConfigCheck()) {
            return;
        }

        if ( ! is_bool(Config::get('service-health-checking.request-insurance.perform-check'))) {
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
            $value = Config::get($fullKey);

            if ( ! is_int($value) || $value < 0) {
                $this->configIsOk = false;
                $this->errorMessages[] = sprintf('%s must be an integer greater than or equal to 0', $key);
            }
        }

        if ($this->activeThresholdConfigIsOk()) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'active-thresholds.warn must not be greater than active-thresholds.fail';
        }

        if ($this->failedThresholdConfigIsOk()) {
            $this->configIsOk = false;
            $this->errorMessages[] = 'failed-thresholds.warn must not be greater than failed-thresholds.fail';
        }
    }

    /**
     * Checks if the active threshold config is ok
     *
     * @return bool
     */
    private function activeThresholdConfigIsOk(): bool
    {
        return $this->getThreshold(self::TYPE_ACTIVE, self::SEVERITY_WARN) != 0 &&
            $this->getThreshold(self::TYPE_ACTIVE, self::SEVERITY_FAIL) != 0 &&
            $this->getThreshold(self::TYPE_ACTIVE, self::SEVERITY_WARN) > $this->getThreshold(self::TYPE_ACTIVE, self::SEVERITY_FAIL);
    }

    /**
     * Checks if the failed threshold config is ok
     *
     * @return bool
     */
    private function failedThresholdConfigIsOk(): bool
    {
        return $this->getThreshold(self::TYPE_FAILED, self::SEVERITY_WARN) != 0 &&
            $this->getThreshold(self::TYPE_FAILED, self::SEVERITY_FAIL) != 0 &&
            $this->getThreshold(self::TYPE_FAILED, self::SEVERITY_WARN) > $this->getThreshold(self::TYPE_FAILED, self::SEVERITY_FAIL);
    }

    /**
     * Fetches threshold value from config
     *
     * @param string $type
     * @param string $severity
     *
     * @return Repository|Application|mixed
     */
    private function getThreshold(string $type, string $severity)
    {
        $configKey = sprintf('service-health-checking.request-insurance.%s-thresholds.%s', $type, $severity);

        return Config::get($configKey);
    }
}
