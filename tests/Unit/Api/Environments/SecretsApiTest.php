<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Environments\SecretsApi;

describe('Environment SecretsApi', function () {
    it('lists secrets', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/secrets');
        });

        $api = new SecretsApi($http);
        $api->list('site-123', 'env-456');
    });

    it('creates a secret', function () {
        $http = createHttpClient(['id' => 'secret-789'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/secrets');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('DB_HOST');
            expect($body['value'])->toBe('localhost');
        });

        $api = new SecretsApi($http);
        $api->create('site-123', 'env-456', ['name' => 'DB_HOST', 'value' => 'localhost']);
    });

    it('gets a secret', function () {
        $http = createHttpClient(['id' => 'secret-789'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/secrets/secret-789');
        });

        $api = new SecretsApi($http);
        $api->get('site-123', 'env-456', 'secret-789');
    });

    it('updates a secret', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/secrets/secret-789');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['value'])->toBe('new-value');
        });

        $api = new SecretsApi($http);
        $api->update('site-123', 'env-456', 'secret-789', ['value' => 'new-value']);
    });

    it('deletes a secret', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/secrets/secret-789');
        });

        $api = new SecretsApi($http);
        $api->delete('site-123', 'env-456', 'secret-789');
    });
});
