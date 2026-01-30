<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;

final class ApiKeysApi
{
    private const BASE_PATH = '/api/v1/vector/api-keys';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List API keys.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->http->get(self::BASE_PATH);
    }

    /**
     * Create an API key.
     *
     * @param  array{name: string, scopes?: string[]}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post(self::BASE_PATH, $data);
    }

    /**
     * Delete an API key.
     *
     * @return array<string, mixed>
     */
    public function delete(string $keyId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$keyId}");
    }
}
