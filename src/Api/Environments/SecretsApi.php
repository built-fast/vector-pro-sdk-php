<?php

declare(strict_types=1);

namespace VectorPro\Api\Environments;

use VectorPro\HttpClient;
use VectorPro\Response\Secret;

final class SecretsApi
{
    private const ENVIRONMENTS_BASE_PATH = '/api/v1/vector/environments';

    private const SECRETS_BASE_PATH = '/api/v1/vector/secrets';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List secrets for an environment.
     *
     * @return Secret[]
     */
    public function list(string $environmentId): array
    {
        $response = $this->http->get(
            self::ENVIRONMENTS_BASE_PATH."/{$environmentId}/secrets"
        );

        return array_map(Secret::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Create a secret for an environment.
     *
     * @param  array{name: string, value: string}  $data
     */
    public function create(string $environmentId, array $data): Secret
    {
        $response = $this->http->post(
            self::ENVIRONMENTS_BASE_PATH."/{$environmentId}/secrets",
            $data
        );

        return Secret::fromArray($response);
    }

    /**
     * Get a secret.
     */
    public function get(string $secretId): Secret
    {
        $response = $this->http->get(
            self::SECRETS_BASE_PATH."/{$secretId}"
        );

        return Secret::fromArray($response);
    }

    /**
     * Update a secret.
     *
     * @param  array{value: string}  $data
     */
    public function update(string $secretId, array $data): Secret
    {
        $response = $this->http->put(
            self::SECRETS_BASE_PATH."/{$secretId}",
            $data
        );

        return Secret::fromArray($response);
    }

    /**
     * Delete a secret.
     *
     * @return array<string, mixed>
     */
    public function delete(string $secretId): array
    {
        return $this->http->delete(
            self::SECRETS_BASE_PATH."/{$secretId}"
        );
    }
}
