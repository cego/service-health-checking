<?php

namespace Cego\ServiceHealthChecking;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<array-key, mixed>
 */
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
     *
     * @return HealthResponse
     */
    public function addHealthCheckResponse(HealthCheckResponse $response): HealthResponse
    {
        $this->checkResponses[] = $response;
        $this->status->setStatusIfWorse($response->getStatus());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->getText(),
            'checks' => array_map(fn (HealthCheckResponse $checkResponse) => $checkResponse->toArray(), $this->checkResponses),
        ];
    }
}
