<?php
namespace Cego\ServiceHealthChecking;

use Composer\InstalledVersions;
use Cego\RequestInsurance\Enums\State;
use Illuminate\Support\Facades\Config;
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

        $status = HealthStatus::pass();

        if ($warnThreshold != 0 && $count >= $warnThreshold) {
            $status->setStatusWarning();
        }

        if ($failThreshold != 0 && $count >= $failThreshold) {
            $status->setStatusFail();
        }

        return $status->setMessage(sprintf('Active Request Insurances count: %s. Warn threshold: %s, fail threshold: %s.',
            $count,
            $warnThreshold == 0 ? 'disabled' : $warnThreshold,
            $failThreshold == 0 ? 'disabled' : $failThreshold
        ));
    }
}
