<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;

final class SecretsApi
{
    private const BASE_PATH = '/api/v1/vector/global-secrets';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List global secrets.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->http->get(self::BASE_PATH);
    }

    /**
     * Create a global secret.
     *
     * @param  array{name: string, value: string}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post(self::BASE_PATH, $data);
    }

    /**
     * Get a global secret.
     *
     * @return array<string, mixed>
     */
    public function get(string $secretId): array
    {
        return $this->http->get(self::BASE_PATH."/{$secretId}");
    }

    /**
     * Update a global secret.
     *
     * @param  array{value: string}  $data
     * @return array<string, mixed>
     */
    public function update(string $secretId, array $data): array
    {
        return $this->http->put(self::BASE_PATH."/{$secretId}", $data);
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
