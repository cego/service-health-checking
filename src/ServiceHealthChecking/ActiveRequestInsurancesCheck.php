<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Config;
use Cego\RequestInsurance\Enums\State;
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
        return ! InstalledVersions::isInstalled('cego/request-insurance') || ! Config::get('service-health-checking.request-insurance.perform-check');
    }

    /**
     * Gets the active RI count
     *
     * @return int
     */
    protected function getCount(): int
    {
        /** @phpstan-ignore-next-line  */
        return RequestInsurance::query()->whereIn('state', [State::READY, State::WAITING, State::PENDING, State::PROCESSING])->count();
    }

    /**
     * @inheritDoc
     */
    protected function check(): HealthStatus
    {
        $count = $this->getCount();

        $warnThreshold = Config::get('service-health-checking.request-insurance.active-thresholds.warn', 0);
        $failThreshold = Config::get('service-health-checking.request-insurance.active-thresholds.fail', 0);

        if ($failThreshold != 0 && $count >= $failThreshold) {
            return HealthStatus::fail()->setMessage(sprintf('Active Request Insurances count: %s. Threshold: %s', $count, $failThreshold));
        }

        if ($warnThreshold != 0 && $count >= $warnThreshold) {
            return HealthStatus::warn()->setMessage(sprintf('Active Request Insurances count: %s. Threshold: %s', $count, $warnThreshold));
        }

        return HealthStatus::pass();
    }
}
