<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\DatabaseApi;
use VectorPro\Response\ExportStatus;
use VectorPro\Response\ImportStatus;

describe('DatabaseApi', function () {
    it('imports database from URL', function () {
        $http = createHttpClient([
            'id' => 'import-123',
            'status' => 'importing',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/import');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['url'])->toBe('https://example.com/backup.sql');
        });

        $api = new DatabaseApi($http);
        $result = $api->import('site-123', ['url' => 'https://example.com/backup.sql']);

        expect($result)->toBeInstanceOf(ImportStatus::class);
        expect($result->status)->toBe('importing');
    });

    it('imports database with search/replace', function () {
        $http = createHttpClient([
            'id' => 'import-123',
            'status' => 'importing',
        ], function (RequestInterface $request) {
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['search_replace'])->toBe([
                ['search' => 'old.com', 'replace' => 'new.com'],
            ]);
        });

        $api = new DatabaseApi($http);
        $api->import('site-123', [
            'url' => 'https://example.com/backup.sql',
            'search_replace' => [['search' => 'old.com', 'replace' => 'new.com']],
        ]);
    });

    it('creates import session', function () {
        $http = createHttpClient([
            'id' => 'import-456',
            'upload_url' => 'https://...',
            'filename' => 'backup.sql',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['filename'])->toBe('backup.sql');
        });

        $api = new DatabaseApi($http);
        $result = $api->createImportSession('site-123', ['filename' => 'backup.sql']);

        expect($result)->toBeInstanceOf(ImportStatus::class);
        expect($result->upload_url)->toBe('https://...');
    });

    it('runs import', function () {
        $http = createHttpClient([
            'id' => 'import-456',
            'status' => 'running',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports/import-456/run');
        });

        $api = new DatabaseApi($http);
        $result = $api->runImport('site-123', 'import-456');

        expect($result)->toBeInstanceOf(ImportStatus::class);
    });

    it('gets import status', function () {
        $http = createHttpClient([
            'id' => 'import-456',
            'status' => 'completed',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports/import-456');
        });

        $api = new DatabaseApi($http);
        $result = $api->getImportStatus('site-123', 'import-456');

        expect($result)->toBeInstanceOf(ImportStatus::class);
        expect($result->status)->toBe('completed');
    });

    it('creates export', function () {
        $http = createHttpClient([
            'id' => 'export-789',
            'status' => 'pending',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/export');
        });

        $api = new DatabaseApi($http);
        $result = $api->createExport('site-123');

        expect($result)->toBeInstanceOf(ExportStatus::class);
        expect($result->id)->toBe('export-789');
    });

    it('gets export status', function () {
        $http = createHttpClient([
            'id' => 'export-789',
            'status' => 'completed',
            'download_url' => 'https://...',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/exports/export-789');
        });

        $api = new DatabaseApi($http);
        $result = $api->getExportStatus('site-123', 'export-789');

        expect($result)->toBeInstanceOf(ExportStatus::class);
        expect($result->download_url)->toBe('https://...');
    });

    it('resets database password', function () {
        $http = createHttpClient(['password' => 'new-pass'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/database/reset-password');
        });

        $api = new DatabaseApi($http);
        $api->resetPassword('site-123');
    });
});
