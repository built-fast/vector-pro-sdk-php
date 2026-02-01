<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;
use VectorPro\Response\Deployment;
use VectorPro\Response\PaginatedResponse;

final class DeploymentsApi
{
    private const ENVIRONMENTS_BASE_PATH = '/api/v1/vector/environments';

    private const DEPLOYMENTS_BASE_PATH = '/api/v1/vector/deployments';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List deployments for an environment.
     *
     * @param  array{per_page?: int, page?: int}  $options
     * @return PaginatedResponse<Deployment>
     */
    public function list(string $environmentId, array $options = []): PaginatedResponse
    {
        $response = $this->http->get(
            self::ENVIRONMENTS_BASE_PATH."/{$environmentId}/deployments",
            $options
        );

        return PaginatedResponse::fromArray($response, Deployment::fromArray(...));
    }

    /**
     * Create a deployment for an environment.
     *
     * @param  array{description?: string}  $data
     */
    public function create(string $environmentId, array $data = []): Deployment
    {
        $response = $this->http->post(
            self::ENVIRONMENTS_BASE_PATH."/{$environmentId}/deployments",
            $data
        );

        return Deployment::fromArray($response);
    }

    /**
     * Get a deployment.
     */
    public function get(string $deploymentId): Deployment
    {
        $response = $this->http->get(
            self::DEPLOYMENTS_BASE_PATH."/{$deploymentId}"
        );

        return Deployment::fromArray($response);
    }

    /**
     * Rollback to a previous deployment.
     *
     * @param  array{deployment_id: string}  $data
     */
    public function rollback(string $environmentId, array $data): Deployment
    {
        $response = $this->http->post(
            self::ENVIRONMENTS_BASE_PATH."/{$environmentId}/rollback",
            $data
        );

        return Deployment::fromArray($response);
    }
}
