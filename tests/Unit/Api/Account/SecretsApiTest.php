<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Account\SecretsApi;
use VectorPro\Response\Secret;

describe('Account SecretsApi', function () {
    it('lists global secrets', function () {
        $http = createHttpClient([
            'data' => [
                ['id' => 'secret-1', 'name' => 'API_KEY'],
            ],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets');
        });

        $api = new SecretsApi($http);
        $result = $api->list();

        expect($result)->toBeArray();
        expect($result)->toHaveCount(1);
        expect($result[0])->toBeInstanceOf(Secret::class);
    });

    it('creates a global secret', function () {
        $http = createHttpClient([
            'id' => 'secret-123',
            'name' => 'API_KEY',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('API_KEY');
            expect($body['value'])->toBe('secret-value');
        });

        $api = new SecretsApi($http);
        $result = $api->create(['name' => 'API_KEY', 'value' => 'secret-value']);

        expect($result)->toBeInstanceOf(Secret::class);
        expect($result->id)->toBe('secret-123');
        expect($result->name)->toBe('API_KEY');
    });

    it('gets a global secret', function () {
        $http = createHttpClient([
            'id' => 'secret-123',
            'name' => 'API_KEY',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets/secret-123');
        });

        $api = new SecretsApi($http);
        $result = $api->get('secret-123');

        expect($result)->toBeInstanceOf(Secret::class);
        expect($result->id)->toBe('secret-123');
    });

    it('updates a global secret', function () {
        $http = createHttpClient([
            'id' => 'secret-123',
            'name' => 'API_KEY',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/global-secrets/secret-123');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['value'])->toBe('new-value');
        });

        $api = new SecretsApi($http);
        $result = $api->update('secret-123', ['value' => 'new-value']);

        expect($result)->toBeInstanceOf(Secret::class);
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
