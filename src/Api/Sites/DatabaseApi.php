<?php

declare(strict_types=1);

namespace VectorPro\Api\Sites;

use VectorPro\HttpClient;
use VectorPro\Response\ExportStatus;
use VectorPro\Response\ImportStatus;

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
     */
    public function import(string $siteId, array $data): ImportStatus
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/db/import", $data);

        return ImportStatus::fromArray($response);
    }

    /**
     * Create a database import session for uploading a file.
     *
     * @param  array{filename: string, search_replace?: array<array{search: string, replace: string}>}  $data
     */
    public function createImportSession(string $siteId, array $data): ImportStatus
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/db/imports", $data);

        return ImportStatus::fromArray($response);
    }

    /**
     * Run a previously created import session.
     */
    public function runImport(string $siteId, string $importId): ImportStatus
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/db/imports/{$importId}/run");

        return ImportStatus::fromArray($response);
    }

    /**
     * Get the status of a database import.
     */
    public function getImportStatus(string $siteId, string $importId): ImportStatus
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}/db/imports/{$importId}");

        return ImportStatus::fromArray($response);
    }

    /**
     * Create a database export.
     *
     * @param  array{format?: string}  $data
     */
    public function createExport(string $siteId, array $data = []): ExportStatus
    {
        $response = $this->http->post(self::BASE_PATH."/{$siteId}/db/export", $data);

        return ExportStatus::fromArray($response);
    }

    /**
     * Get the status of a database export.
     */
    public function getExportStatus(string $siteId, string $exportId): ExportStatus
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}/db/exports/{$exportId}");

        return ExportStatus::fromArray($response);
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
