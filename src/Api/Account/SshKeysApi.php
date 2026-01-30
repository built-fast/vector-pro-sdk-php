<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;

final class SshKeysApi
{
    private const BASE_PATH = '/api/v1/vector/ssh-keys';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List account SSH keys.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->http->get(self::BASE_PATH);
    }

    /**
     * Create an SSH key.
     *
     * @param  array{name: string, public_key: string}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post(self::BASE_PATH, $data);
    }

    /**
     * Get an SSH key.
     *
     * @return array<string, mixed>
     */
    public function get(string $keyId): array
    {
        return $this->http->get(self::BASE_PATH."/{$keyId}");
    }

    /**
     * Delete an SSH key.
     *
     * @return array<string, mixed>
     */
    public function delete(string $keyId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$keyId}");
    }
}
