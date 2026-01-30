<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\Api\Environments\DeploymentsApi;
use VectorPro\Api\Environments\SecretsApi;
use VectorPro\HttpClient;
use VectorPro\Response\Environment;

final class EnvironmentsApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public readonly DeploymentsApi $deployments;

    public readonly SecretsApi $secrets;

    public function __construct(
        private readonly HttpClient $http,
    ) {
        $this->deployments = new DeploymentsApi($http);
        $this->secrets = new SecretsApi($http);
    }

    /**
     * List environments for a site.
     *
     * @return Environment[]
     */
    public function list(string $siteId): array
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}/environments");

        return array_map(Environment::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Get an environment.
     */
    public function get(string $siteId, string $environmentId): Environment
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}/environments/{$environmentId}");

        return Environment::fromArray($response);
    }

    /**
     * Create an environment.
     *
     * @param  array{name: string, php_version: string, is_production?: bool, custom_domain?: string}  $data
     */
    public function create(string $siteId, array $data): Environment
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/environments", $data);

        return Environment::fromArray($response);
    }

    /**
     * Update an environment.
     *
     * @param  array{php_version?: string, custom_domain?: string}  $data
     */
    public function update(string $siteId, string $environmentId, array $data): Environment
    {
        $response = $this->http->put(self::BASE_PATH."/{$siteId}/environments/{$environmentId}", $data);

        return Environment::fromArray($response);
    }

    /**
     * Delete an environment.
     *
     * @return array<string, mixed>
     */
    public function delete(string $siteId, string $environmentId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/environments/{$environmentId}");
    }

    /**
     * Reset database password for an environment.
     *
     * @return array<string, mixed>
     */
    public function resetDatabasePassword(string $siteId, string $environmentId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/environments/{$environmentId}/database/reset-password");
    }
}
