<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Message\RequestInterface;
use VectorPro\HttpClient;
use VectorPro\VectorProClientConfig;

describe('HttpClient', function () {
    it('sends GET requests with correct headers', function () {
        $http = createHttpClient(['data' => 'test'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/test');
            expect($request->getHeaderLine('Authorization'))->toBe('Bearer test-api-key');
            expect($request->getHeaderLine('Accept'))->toBe('application/json');
            expect($request->getHeaderLine('Content-Type'))->toBe('application/json');
        });

        $result = $http->get('/api/v1/test');

        expect($result)->toBe(['data' => 'test']);
    });

    it('sends GET requests with query parameters', function () {
        $http = createHttpClient(['data' => 'test'], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('page=1&per_page=10');
        });

        $http->get('/api/v1/test', ['page' => 1, 'per_page' => 10]);
    });

    it('sends POST requests with JSON body', function () {
        $http = createHttpClient(['id' => '123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getBody()->getContents())->toBe('{"name":"test"}');
        });

        $result = $http->post('/api/v1/test', ['name' => 'test']);

        expect($result)->toBe(['id' => '123']);
    });

    it('sends POST requests without body when data is empty', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getBody()->getContents())->toBe('');
        });

        $http->post('/api/v1/test');
    });

    it('sends PUT requests with JSON body', function () {
        $http = createHttpClient(['updated' => true], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getBody()->getContents())->toBe('{"name":"updated"}');
        });

        $result = $http->put('/api/v1/test', ['name' => 'updated']);

        expect($result)->toBe(['updated' => true]);
    });

    it('sends PUT requests without body when data is empty', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getBody()->getContents())->toBe('');
        });

        $http->put('/api/v1/test');
    });

    it('sends DELETE requests', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/test/123');
        });

        $http->delete('/api/v1/test/123');
    });

    it('returns empty array for empty response body', function () {
        $http = createHttpClient([]);

        $result = $http->get('/api/v1/test');

        expect($result)->toBe([]);
    });

    it('uses custom base URL from config', function () {
        $config = new VectorProClientConfig('test-key', 'https://custom.api.com');
        $factory = new HttpFactory;
        $mockClient = mockHttpClient([], function (RequestInterface $request) {
            expect((string) $request->getUri())->toBe('https://custom.api.com/api/v1/test');
        });

        $http = new HttpClient($config, $mockClient, $factory, $factory);
        $http->get('/api/v1/test');
    });
});
