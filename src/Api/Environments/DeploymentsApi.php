<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;

final class DeploymentsApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List deployments for an environment.
     *
     * @param  array{per_page?: int, page?: int}  $options
     * @return array<string, mixed>
     */
    public function list(string $siteId, string $environmentId, array $options = []): array
    {
        return $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments",
            $options
        );
    }

    /**
     * Create a deployment for an environment.
     *
     * @param  array{description?: string}  $data
     * @return array<string, mixed>
     */
    public function create(string $siteId, string $environmentId, array $data = []): array
    {
        return $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments",
            $data
        );
    }

    /**
     * Get a deployment.
     *
     * @return array<string, mixed>
     */
    public function get(string $siteId, string $environmentId, string $deploymentId): array
    {
        return $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments/{$deploymentId}"
        );
    }

    /**
     * Rollback to a previous deployment.
     *
     * @param  array{deployment_id: string}  $data
     * @return array<string, mixed>
     */
    public function rollback(string $siteId, string $environmentId, array $data): array
    {
        return $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/rollback",
            $data
        );
    }
}
