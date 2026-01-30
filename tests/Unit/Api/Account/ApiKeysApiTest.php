<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Account\ApiKeysApi;

describe('ApiKeysApi', function () {
    it('lists API keys', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/api-keys');
        });

        $api = new ApiKeysApi($http);
        $api->list();
    });

    it('creates an API key', function () {
        $http = createHttpClient(['id' => 'key-123', 'token' => 'secret'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/api-keys');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('CI/CD Key');
        });

        $api = new ApiKeysApi($http);
        $api->create(['name' => 'CI/CD Key']);
    });

    it('deletes an API key', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/api-keys/key-123');
        });

        $api = new ApiKeysApi($http);
        $api->delete('key-123');
    });
});
