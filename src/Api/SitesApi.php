<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\Api\Sites\DatabaseApi;
use VectorPro\Api\Sites\SshKeysApi;
use VectorPro\Api\Sites\SslApi;
use VectorPro\Api\Sites\WafApi;
use VectorPro\HttpClient;
use VectorPro\Response\PaginatedResponse;
use VectorPro\Response\Site;

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
     * @return PaginatedResponse<Site>
     */
    public function list(array $options = []): PaginatedResponse
    {
        $response = $this->http->get(self::BASE_PATH, $options);

        return PaginatedResponse::fromArray($response, Site::fromArray(...));
    }

    /**
     * Get a site by ID.
     */
    public function get(string $siteId): Site
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}");

        return Site::fromArray($response);
    }

    /**
     * Create a new site.
     *
     * @param  array{partner_customer_id: string, dev_php_version: string, tags?: string[]}  $data
     */
    public function create(array $data): Site
    {
        $response = $this->http->post(self::BASE_PATH, $data);

        return Site::fromArray($response);
    }

    /**
     * Update a site.
     *
     * @param  array{partner_customer_id?: string, dev_php_version?: string, tags?: string[]}  $data
     */
    public function update(string $siteId, array $data): Site
    {
        $response = $this->http->put(self::BASE_PATH."/{$siteId}", $data);

        return Site::fromArray($response);
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
     */
    public function clone(string $siteId, ?array $data = null): Site
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/clone", $data ?? []);

        return Site::fromArray($response);
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
