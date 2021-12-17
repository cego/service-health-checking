<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
use Cego\RequestInsurance\Models\RequestInsurance;

class ActiveRequestInsurancesCheck extends BaseHealthCheck
{
    /**
     * @inheritDoc
     */
    protected function getDescription(): string
    {
        return 'Checks that active request insurances is below threshold';
    }

    /**
     * @inheritDoc
     */
    public function shouldSkip(): bool
    {
        return ! InstalledVersions::isInstalled('cego/request-insurance') || ! config('service-health-checking.request-insurance.perform-check');
    }

    /**
     * Gets the active RI count
     *
     * @return int
     */
    protected function getCount(): int
    {
        /** @phpstan-ignore-next-line  */
        return RequestInsurance::where('completed_at', null)
            ->where('abandoned_at', null)
            ->where('paused_at', null)
            ->count();
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        $count = $this->getCount();

        $warnThreshold = config('service-health-checking.request-insurance.active-thresholds.warn', 0);
        $failThreshold = config('service-health-checking.request-insurance.active-thresholds.fail', 0);

        if ($count >= $failThreshold) {
            return HealthStatus::fail()->setMessage(sprintf('Active Request Insurances count: %s. Threshold: %s', $count, $failThreshold));
        }

        if ($count >= $warnThreshold) {
            return HealthStatus::warn()->setMessage(sprintf('Active Request Insurances count: %s. Threshold: %s', $count, $warnThreshold));
        }

        return HealthStatus::pass();
    }
}
