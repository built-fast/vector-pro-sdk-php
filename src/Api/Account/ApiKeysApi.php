<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;
use VectorPro\Response\ApiKey;

final class ApiKeysApi
{
    private const BASE_PATH = '/api/v1/vector/api-keys';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List API keys.
     *
     * @return ApiKey[]
     */
    public function list(): array
    {
        $response = $this->http->get(self::BASE_PATH);

        return array_map(ApiKey::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Create an API key.
     *
     * @param  array{name: string, scopes?: string[]}  $data
     */
    public function create(array $data): ApiKey
    {
        $response = $this->http->post(self::BASE_PATH, $data);

        return ApiKey::fromArray($response);
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
