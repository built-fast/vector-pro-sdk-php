<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\EnvironmentsApi;

describe('EnvironmentsApi', function () {
    it('lists environments', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments');
        });

        $api = new EnvironmentsApi($http);
        $api->list('site-123');
    });

    it('gets an environment', function () {
        $http = createHttpClient(['id' => 'env-456'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456');
        });

        $api = new EnvironmentsApi($http);
        $api->get('site-123', 'env-456');
    });

    it('creates an environment', function () {
        $http = createHttpClient(['id' => 'new-env'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('staging');
            expect($body['php_version'])->toBe('8.3');
        });

        $api = new EnvironmentsApi($http);
        $api->create('site-123', ['name' => 'staging', 'php_version' => '8.3']);
    });

    it('updates an environment', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['php_version'])->toBe('8.2');
        });

        $api = new EnvironmentsApi($http);
        $api->update('site-123', 'env-456', ['php_version' => '8.2']);
    });

    it('deletes an environment', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456');
        });

        $api = new EnvironmentsApi($http);
        $api->delete('site-123', 'env-456');
    });

    it('resets database password', function () {
        $http = createHttpClient(['password' => 'new-pass'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/database/reset-password');
        });

        $api = new EnvironmentsApi($http);
        $api->resetDatabasePassword('site-123', 'env-456');
    });
});
