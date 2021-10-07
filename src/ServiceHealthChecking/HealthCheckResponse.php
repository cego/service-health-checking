<?php

namespace Cego\ServiceHealthChecking;

use Illuminate\Contracts\Support\Arrayable;

class HealthCheckResponse implements Arrayable
{
    private HealthStatus $status;
    private string $name;
    private string $description;

    public function __construct(HealthStatus $status, string $healthCheckName, string $healthCheckDescription)
    {
        $this->status = $status;
        $this->name = $healthCheckName;
        $this->description = $healthCheckDescription;
    }

    /**
     * Gets the health status
     *
     * @return HealthStatus
     */
    public function getStatus(): HealthStatus
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'status'      => $this->status->getText(),
            'name'        => $this->name,
            'description' => $this->description,
            'message'     => $this->status->getMessage(),
        ];
    }
}
