<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\WebhooksApi;

describe('WebhooksApi', function () {
    it('lists webhooks', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks');
        });

        $api = new WebhooksApi($http);
        $api->list();
    });

    it('gets a webhook', function () {
        $http = createHttpClient(['id' => 'hook-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123');
        });

        $api = new WebhooksApi($http);
        $api->get('hook-123');
    });

    it('creates a webhook', function () {
        $http = createHttpClient(['id' => 'hook-123'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['url'])->toBe('https://example.com/webhook');
            expect($body['events'])->toBe(['site.created']);
        });

        $api = new WebhooksApi($http);
        $api->create(['url' => 'https://example.com/webhook', 'events' => ['site.created']]);
    });

    it('updates a webhook', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['enabled'])->toBe(false);
        });

        $api = new WebhooksApi($http);
        $api->update('hook-123', ['enabled' => false]);
    });

    it('deletes a webhook', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123');
        });

        $api = new WebhooksApi($http);
        $api->delete('hook-123');
    });

    it('lists webhook logs', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123/logs');
        });

        $api = new WebhooksApi($http);
        $api->listLogs('hook-123');
    });

    it('lists webhook logs with pagination', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('per_page=10&page=2');
        });

        $api = new WebhooksApi($http);
        $api->listLogs('hook-123', ['per_page' => 10, 'page' => 2]);
    });

    it('rotates webhook secret', function () {
        $http = createHttpClient(['secret' => 'new-secret'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123/rotate-secret');
        });

        $api = new WebhooksApi($http);
        $api->rotateSecret('hook-123');
    });
});
