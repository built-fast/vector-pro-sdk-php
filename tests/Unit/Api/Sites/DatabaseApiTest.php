<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\DatabaseApi;

describe('DatabaseApi', function () {
    it('imports database from URL', function () {
        $http = createHttpClient(['status' => 'importing'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/import');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['url'])->toBe('https://example.com/backup.sql');
        });

        $api = new DatabaseApi($http);
        $api->import('site-123', ['url' => 'https://example.com/backup.sql']);
    });

    it('imports database with search/replace', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
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
        $http = createHttpClient(['upload_url' => 'https://...'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['filename'])->toBe('backup.sql');
        });

        $api = new DatabaseApi($http);
        $api->createImportSession('site-123', ['filename' => 'backup.sql']);
    });

    it('runs import', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports/import-456/run');
        });

        $api = new DatabaseApi($http);
        $api->runImport('site-123', 'import-456');
    });

    it('gets import status', function () {
        $http = createHttpClient(['status' => 'completed'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/imports/import-456');
        });

        $api = new DatabaseApi($http);
        $api->getImportStatus('site-123', 'import-456');
    });

    it('creates export', function () {
        $http = createHttpClient(['id' => 'export-789'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/export');
        });

        $api = new DatabaseApi($http);
        $api->createExport('site-123');
    });

    it('gets export status', function () {
        $http = createHttpClient(['download_url' => 'https://...'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/db/exports/export-789');
        });

        $api = new DatabaseApi($http);
        $api->getExportStatus('site-123', 'export-789');
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
