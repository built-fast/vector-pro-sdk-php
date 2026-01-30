<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;

final class SecretsApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List secrets for an environment.
     *
     * @return array<string, mixed>
     */
    public function list(string $siteId, string $environmentId): array
    {
        return $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets"
        );
    }

    /**
     * Create a secret for an environment.
     *
     * @param  array{name: string, value: string}  $data
     * @return array<string, mixed>
     */
    public function create(string $siteId, string $environmentId, array $data): array
    {
        return $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets",
            $data
        );
    }

    /**
     * Get a secret.
     *
     * @return array<string, mixed>
     */
    public function get(string $siteId, string $environmentId, string $secretId): array
    {
        return $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets/{$secretId}"
        );
    }

    /**
     * Update a secret.
     *
     * @param  array{value: string}  $data
     * @return array<string, mixed>
     */
    public function update(string $siteId, string $environmentId, string $secretId, array $data): array
    {
        return $this->http->put(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets/{$secretId}",
            $data
        );
    }

    /**
     * Delete a secret.
     *
     * @return array<string, mixed>
     */
    public function delete(string $siteId, string $environmentId, string $secretId): array
    {
        return $this->http->delete(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets/{$secretId}"
        );
    }
}
