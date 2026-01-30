<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\Api\Sites\DatabaseApi;
use VectorPro\Api\Sites\SshKeysApi;
use VectorPro\Api\Sites\SslApi;
use VectorPro\Api\Sites\WafApi;
use VectorPro\HttpClient;

final class SitesApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public readonly DatabaseApi $db;

    public readonly WafApi $waf;

    public readonly SshKeysApi $sshKeys;

    public readonly SslApi $ssl;

    public function __construct(
        private readonly HttpClient $http,
    ) {
        $this->db = new DatabaseApi($http);
        $this->waf = new WafApi($http);
        $this->sshKeys = new SshKeysApi($http);
        $this->ssl = new SslApi($http);
    }

    /**
     * List all sites.
     *
     * @param  array{per_page?: int, page?: int}  $options
     * @return array<string, mixed>
     */
    public function list(array $options = []): array
    {
        return $this->http->get(self::BASE_PATH, $options);
    }

    /**
     * Get a site by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}");
    }

    /**
     * Create a new site.
     *
     * @param  array{partner_customer_id: string, dev_php_version: string, tags?: string[]}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post(self::BASE_PATH, $data);
    }

    /**
     * Update a site.
     *
     * @param  array{partner_customer_id?: string, dev_php_version?: string, tags?: string[]}  $data
     * @return array<string, mixed>
     */
    public function update(string $siteId, array $data): array
    {
        return $this->http->put(self::BASE_PATH."/{$siteId}", $data);
    }

    /**
     * Delete a site.
     *
     * @return array<string, mixed>
     */
    public function delete(string $siteId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}");
    }

    /**
     * Clone a site.
     *
     * @param  array{partner_customer_id?: string}|null  $data
     * @return array<string, mixed>
     */
    public function clone(string $siteId, ?array $data = null): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/clone", $data ?? []);
    }

    /**
     * Suspend a site.
     *
     * @return array<string, mixed>
     */
    public function suspend(string $siteId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/suspend");
    }

    /**
     * Unsuspend a site.
     *
     * @return array<string, mixed>
     */
    public function unsuspend(string $siteId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/unsuspend");
    }

    /**
     * Reset SFTP password for a site.
     *
     * @return array<string, mixed>
     */
    public function resetSftpPassword(string $siteId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/sftp/reset-password");
    }

    /**
     * Get logs for a site.
     *
     * @param  array{type?: string, lines?: int}  $options
     * @return array<string, mixed>
     */
    public function getLogs(string $siteId, array $options = []): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/logs", $options);
    }

    /**
     * Purge CDN cache for a site.
     *
     * @param  array{paths?: string[]}|null  $options
     * @return array<string, mixed>
     */
    public function purgeCache(string $siteId, ?array $options = null): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/purge-cache", $options ?? []);
    }
}
