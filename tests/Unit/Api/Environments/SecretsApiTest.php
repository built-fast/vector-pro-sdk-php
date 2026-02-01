<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Environments\SecretsApi;
use VectorPro\Response\Secret;

describe('Environment SecretsApi', function () {
    it('lists secrets', function () {
        $http = createHttpClient([
            'data' => [
                ['id' => 'secret-1', 'name' => 'DB_HOST'],
            ],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/environments/env-456/secrets');
        });

        $api = new SecretsApi($http);
        $result = $api->list('env-456');

        expect($result)->toBeArray();
        expect($result)->toHaveCount(1);
        expect($result[0])->toBeInstanceOf(Secret::class);
    });

    it('creates a secret', function () {
        $http = createHttpClient([
            'id' => 'secret-789',
            'name' => 'DB_HOST',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/environments/env-456/secrets');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('DB_HOST');
            expect($body['value'])->toBe('localhost');
        });

        $api = new SecretsApi($http);
        $result = $api->create('env-456', ['name' => 'DB_HOST', 'value' => 'localhost']);

        expect($result)->toBeInstanceOf(Secret::class);
        expect($result->id)->toBe('secret-789');
        expect($result->name)->toBe('DB_HOST');
    });

    it('gets a secret', function () {
        $http = createHttpClient([
            'id' => 'secret-789',
            'name' => 'DB_HOST',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/secrets/secret-789');
        });

        $api = new SecretsApi($http);
        $result = $api->get('secret-789');

        expect($result)->toBeInstanceOf(Secret::class);
        expect($result->id)->toBe('secret-789');
    });

    it('updates a secret', function () {
        $http = createHttpClient([
            'id' => 'secret-789',
            'name' => 'DB_HOST',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/secrets/secret-789');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['value'])->toBe('new-value');
        });

        $api = new SecretsApi($http);
        $result = $api->update('secret-789', ['value' => 'new-value']);

        expect($result)->toBeInstanceOf(Secret::class);
    });

    it('deletes a secret', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/secrets/secret-789');
        });

        $api = new SecretsApi($http);
        $api->delete('secret-789');
    });
});
