<?php

namespace Cego\ServiceHealthChecking;

use Illuminate\Contracts\Support\Arrayable;

class HealthResponse implements Arrayable
{
    /**
     * @var HealthStatus
     */
    private HealthStatus $status;

    /**
     * @var HealthCheckResponse[]
     */
    private array $checkResponses = [];

    /**
     * HealthResponse constructor
     */
    public function __construct()
    {
        $this->status = HealthStatus::pass();
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
     * Add a health check response to the health response
     *
     * @param HealthCheckResponse $response
     */
    public function addHealthCheckResponse(HealthCheckResponse $response)
    {
        $this->checkResponses[] = $response;
        $this->status->setStatusIfWorse($response->getStatus());
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->getText(),
            'checks' => array_map(fn (HealthCheckResponse $checkResponse) => $checkResponse->toArray(), $this->checkResponses)
        ];
    }
}
