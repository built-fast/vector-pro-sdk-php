<?php

declare(strict_types=1);

namespace VectorPro\Api\Sites;

use VectorPro\HttpClient;

final class DatabaseApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * Import a database directly from a URL.
     *
     * @param  array{url: string, search_replace?: array<array{search: string, replace: string}>}  $data
     * @return array<string, mixed>
     */
    public function import(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/db/import", $data);
    }

    /**
     * Create a database import session for uploading a file.
     *
     * @param  array{filename: string, search_replace?: array<array{search: string, replace: string}>}  $data
     * @return array<string, mixed>
     */
    public function createImportSession(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/db/imports", $data);
    }

    /**
     * Run a previously created import session.
     *
     * @return array<string, mixed>
     */
    public function runImport(string $siteId, string $importId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/db/imports/{$importId}/run");
    }

    /**
     * Get the status of a database import.
     *
     * @return array<string, mixed>
     */
    public function getImportStatus(string $siteId, string $importId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/db/imports/{$importId}");
    }

    /**
     * Create a database export.
     *
     * @param  array{format?: string}  $data
     * @return array<string, mixed>
     */
    public function createExport(string $siteId, array $data = []): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/db/export", $data);
    }

    /**
     * Get the status of a database export.
     *
     * @return array<string, mixed>
     */
    public function getExportStatus(string $siteId, string $exportId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/db/exports/{$exportId}");
    }

    /**
     * Reset the database password for the development environment.
     *
     * @return array<string, mixed>
     */
    public function resetPassword(string $siteId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/database/reset-password");
    }
}
