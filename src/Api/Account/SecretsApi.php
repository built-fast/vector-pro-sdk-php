<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;
use VectorPro\Response\Secret;

final class SecretsApi
{
    private const BASE_PATH = '/api/v1/vector/global-secrets';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List global secrets.
     *
     * @return Secret[]
     */
    public function list(): array
    {
        $response = $this->http->get(self::BASE_PATH);

        return array_map(Secret::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Create a global secret.
     *
     * @param  array{name: string, value: string}  $data
     */
    public function create(array $data): Secret
    {
        $response = $this->http->post(self::BASE_PATH, $data);

        return Secret::fromArray($response);
    }

    /**
     * Get a global secret.
     */
    public function get(string $secretId): Secret
    {
        $response = $this->http->get(self::BASE_PATH."/{$secretId}");

        return Secret::fromArray($response);
    }

    /**
     * Update a global secret.
     *
     * @param  array{value: string}  $data
     */
    public function update(string $secretId, array $data): Secret
    {
        $response = $this->http->put(self::BASE_PATH."/{$secretId}", $data);

        return Secret::fromArray($response);
    }

    /**
     * Delete a global secret.
     *
     * @return array<string, mixed>
     */
    public function delete(string $secretId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$secretId}");
    }
}
