<?php

declare(strict_types=1);

namespace VectorPro\Api\Sites;

use VectorPro\HttpClient;

final class SshKeysApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List SSH keys attached to a site.
     *
     * @return array<string, mixed>
     */
    public function list(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/ssh-keys");
    }

    /**
     * Add an SSH key to a site.
     *
     * @param  array{ssh_key_id: string}  $data
     * @return array<string, mixed>
     */
    public function add(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/ssh-keys", $data);
    }

    /**
     * Remove an SSH key from a site.
     *
     * @return array<string, mixed>
     */
    public function remove(string $siteId, string $keyId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/ssh-keys/{$keyId}");
    }
}
