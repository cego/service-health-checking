<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
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
        return ! InstalledVersions::isInstalled('cego/request-insurance') || ! config('service-health-checking.request-insurance.perform-check');
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        /** @phpstan-ignore-next-line  */
        $count = RequestInsurance::where('abandoned_at', null)
            ->where('paused_at', '!=', null)
            ->count();

        $warnThreshold = config('service-health-checking.request-insurance.failed-thresholds.warn', 0);
        $failThreshold = config('service-health-checking.request-insurance.failed-thresholds.fail', 0);

        if ($count >= $failThreshold) {
            return HealthStatus::fail()->setMessage(sprintf('Failed Request Insurances count: %s. Threshold: %s', $count, $failThreshold));
        }

        if ($count >= $warnThreshold) {
            return HealthStatus::warn()->setMessage(sprintf('Failed Request Insurances count: %s. Threshold: %s', $count, $warnThreshold));
        }

        return HealthStatus::pass();
    }
}
