<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;
use VectorPro\Response\Secret;

final class SecretsApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List secrets for an environment.
     *
     * @return Secret[]
     */
    public function list(string $siteId, string $environmentId): array
    {
        $response = $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets"
        );

        return array_map(Secret::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Create a secret for an environment.
     *
     * @param  array{name: string, value: string}  $data
     */
    public function create(string $siteId, string $environmentId, array $data): Secret
    {
        $response = $this->http->post(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets",
            $data
        );

        return Secret::fromArray($response);
    }

    /**
     * Get a secret.
     */
    public function get(string $siteId, string $environmentId, string $secretId): Secret
    {
        $response = $this->http->get(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets/{$secretId}"
        );

        return Secret::fromArray($response);
    }

    /**
     * Update a secret.
     *
     * @param  array{value: string}  $data
     */
    public function update(string $siteId, string $environmentId, string $secretId, array $data): Secret
    {
        $response = $this->http->put(
            self::BASE_PATH."/{$siteId}/environments/{$environmentId}/secrets/{$secretId}",
            $data
        );

        return Secret::fromArray($response);
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
