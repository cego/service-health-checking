<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
use Cego\RequestInsurance\Enums\State;
use Illuminate\Support\Facades\Config;
use Composer\Package\Version\VersionParser;
use Cego\RequestInsurance\Models\RequestInsurance;

class FailedRequestInsurancesCheck extends BaseHealthCheck
{
    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks that failed request insurances is below threshold';
    }

    /**
     * @inheritDoc
     */
    public function shouldSkip(): bool
    {
        // Health check requires at least version 0.11.4
        return InstalledVersions::satisfies(new VersionParser(), 'cego/request-insurance', '<0.11.4') || ! Config::get('service-health-checking.request-insurance.perform-check');
    }

    /**
     * Gets the failed RI count
     *
     * @return int
     */
    protected function getCount(): int
    {
        /** @phpstan-ignore-next-line  */
        return RequestInsurance::query()->where('state', State::FAILED)->count();
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        $count = $this->getCount();

        $warnThreshold = Config::get('service-health-checking.request-insurance.failed-thresholds.warn', 0);
        $failThreshold = Config::get('service-health-checking.request-insurance.failed-thresholds.fail', 0);

        if ($failThreshold != 0 && $count >= $failThreshold) {
            return HealthStatus::fail()->setMessage(sprintf('Failed Request Insurances count: %s. Threshold: %s', $count, $failThreshold));
        }

        if ($warnThreshold != 0 && $count >= $warnThreshold) {
            return HealthStatus::warn()->setMessage(sprintf('Failed Request Insurances count: %s. Threshold: %s', $count, $warnThreshold));
        }

        return HealthStatus::pass();
    }
}
