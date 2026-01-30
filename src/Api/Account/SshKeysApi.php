<?php

declare(strict_types=1);

namespace VectorPro\Api\Account;

use VectorPro\HttpClient;
use VectorPro\Response\SshKey;

final class SshKeysApi
{
    private const BASE_PATH = '/api/v1/vector/ssh-keys';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List account SSH keys.
     *
     * @return SshKey[]
     */
    public function list(): array
    {
        $response = $this->http->get(self::BASE_PATH);

        return array_map(SshKey::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Create an SSH key.
     *
     * @param  array{name: string, public_key: string}  $data
     */
    public function create(array $data): SshKey
    {
        $response = $this->http->post(self::BASE_PATH, $data);

        return SshKey::fromArray($response);
    }

    /**
     * Get an SSH key.
     */
    public function get(string $keyId): SshKey
    {
        $response = $this->http->get(self::BASE_PATH."/{$keyId}");

        return SshKey::fromArray($response);
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
