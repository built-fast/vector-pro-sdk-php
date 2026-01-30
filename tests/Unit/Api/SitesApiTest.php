<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\SitesApi;

describe('SitesApi', function () {
    it('lists sites', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites');
        });

        $api = new SitesApi($http);
        $api->list();
    });

    it('lists sites with pagination', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('per_page=10&page=2');
        });

        $api = new SitesApi($http);
        $api->list(['per_page' => 10, 'page' => 2]);
    });

    it('gets a site', function () {
        $http = createHttpClient(['id' => 'site-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123');
        });

        $api = new SitesApi($http);
        $api->get('site-123');
    });

    it('creates a site', function () {
        $http = createHttpClient(['id' => 'new-site'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['partner_customer_id'])->toBe('cust-123');
            expect($body['dev_php_version'])->toBe('8.3');
        });

        $api = new SitesApi($http);
        $api->create(['partner_customer_id' => 'cust-123', 'dev_php_version' => '8.3']);
    });

    it('updates a site', function () {
        $http = createHttpClient(['id' => 'site-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['tags'])->toBe(['production']);
        });

        $api = new SitesApi($http);
        $api->update('site-123', ['tags' => ['production']]);
    });

    it('deletes a site', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123');
        });

        $api = new SitesApi($http);
        $api->delete('site-123');
    });

    it('clones a site', function () {
        $http = createHttpClient(['id' => 'cloned-site'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/clone');
        });

        $api = new SitesApi($http);
        $api->clone('site-123');
    });

    it('suspends a site', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/suspend');
        });

        $api = new SitesApi($http);
        $api->suspend('site-123');
    });

    it('unsuspends a site', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/unsuspend');
        });

        $api = new SitesApi($http);
        $api->unsuspend('site-123');
    });

    it('resets SFTP password', function () {
        $http = createHttpClient(['password' => 'new-pass'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/sftp/reset-password');
        });

        $api = new SitesApi($http);
        $api->resetSftpPassword('site-123');
    });

    it('gets logs', function () {
        $http = createHttpClient(['logs' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/logs');
            expect($request->getUri()->getQuery())->toBe('type=error&lines=100');
        });

        $api = new SitesApi($http);
        $api->getLogs('site-123', ['type' => 'error', 'lines' => 100]);
    });

    it('purges cache', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/purge-cache');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['paths'])->toBe(['/wp-content/*']);
        });

        $api = new SitesApi($http);
        $api->purgeCache('site-123', ['paths' => ['/wp-content/*']]);
    });
});
