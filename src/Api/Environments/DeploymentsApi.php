<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;
use VectorPro\Response\Deployment;
use VectorPro\Response\PaginatedResponse;

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
     * @return PaginatedResponse<Deployment>
     */
    public function list(string $siteId, string $environmentId, array $options = []): PaginatedResponse
    {
        $response = $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments",
            $options
        );

        return PaginatedResponse::fromArray($response, Deployment::fromArray(...));
    }

    /**
     * Create a deployment for an environment.
     *
     * @param  array{description?: string}  $data
     */
    public function create(string $siteId, string $environmentId, array $data = []): Deployment
    {
        $response = $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments",
            $data
        );

        return Deployment::fromArray($response);
    }

    /**
     * Get a deployment.
     */
    public function get(string $siteId, string $environmentId, string $deploymentId): Deployment
    {
        $response = $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/deployments/{$deploymentId}"
        );

        return Deployment::fromArray($response);
    }

    /**
     * Rollback to a previous deployment.
     *
     * @param  array{deployment_id: string}  $data
     */
    public function rollback(string $siteId, string $environmentId, array $data): Deployment
    {
        $response = $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/rollback",
            $data
        );

        return Deployment::fromArray($response);
    }
}
