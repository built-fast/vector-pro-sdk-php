<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Account\SecretsApi;

describe('Account SecretsApi', function () {
    it('lists global secrets', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets');
        });

        $api = new SecretsApi($http);
        $api->list();
    });

    it('creates a global secret', function () {
        $http = createHttpClient(['id' => 'secret-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('API_KEY');
            expect($body['value'])->toBe('secret-value');
        });

        $api = new SecretsApi($http);
        $api->create(['name' => 'API_KEY', 'value' => 'secret-value']);
    });

    it('gets a global secret', function () {
        $http = createHttpClient(['id' => 'secret-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets/secret-123');
        });

        $api = new SecretsApi($http);
        $api->get('secret-123');
    });

    it('updates a global secret', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets/secret-123');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['value'])->toBe('new-value');
        });

        $api = new SecretsApi($http);
        $api->update('secret-123', ['value' => 'new-value']);
    });

    it('deletes a global secret', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets/secret-123');
        });

        $api = new SecretsApi($http);
        $api->delete('secret-123');
    });
});
