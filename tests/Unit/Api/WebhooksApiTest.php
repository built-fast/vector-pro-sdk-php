<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\WebhooksApi;
use VectorPro\Response\Webhook;

describe('WebhooksApi', function () {
    it('lists webhooks', function () {
        $http = createHttpClient([
            'data' => [
                ['id' => 'hook-1', 'url' => 'https://example.com/webhook', 'events' => ['site.created']],
            ],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks');
        });

        $api = new WebhooksApi($http);
        $result = $api->list();

        expect($result)->toBeArray();
        expect($result)->toHaveCount(1);
        expect($result[0])->toBeInstanceOf(Webhook::class);
    });

    it('gets a webhook', function () {
        $http = createHttpClient([
            'id' => 'hook-123',
            'url' => 'https://example.com/webhook',
            'events' => ['site.created'],
            'enabled' => true,
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123');
        });

        $api = new WebhooksApi($http);
        $result = $api->get('hook-123');

        expect($result)->toBeInstanceOf(Webhook::class);
        expect($result->id)->toBe('hook-123');
    });

    it('creates a webhook', function () {
        $http = createHttpClient([
            'id' => 'hook-123',
            'url' => 'https://example.com/webhook',
            'events' => ['site.created'],
            'enabled' => true,
            'secret' => 'whsec_123',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['url'])->toBe('https://example.com/webhook');
            expect($body['events'])->toBe(['site.created']);
        });

        $api = new WebhooksApi($http);
        $result = $api->create(['url' => 'https://example.com/webhook', 'events' => ['site.created']]);

        expect($result)->toBeInstanceOf(Webhook::class);
        expect($result->id)->toBe('hook-123');
        expect($result->secret)->toBe('whsec_123');
    });

    it('updates a webhook', function () {
        $http = createHttpClient([
            'id' => 'hook-123',
            'url' => 'https://example.com/webhook',
            'events' => ['site.created'],
            'enabled' => false,
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('PUT');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['enabled'])->toBe(false);
        });

        $api = new WebhooksApi($http);
        $result = $api->update('hook-123', ['enabled' => false]);

        expect($result)->toBeInstanceOf(Webhook::class);
        expect($result->enabled)->toBe(false);
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
        $http = createHttpClient([
            'id' => 'hook-123',
            'url' => 'https://example.com/webhook',
            'events' => ['site.created'],
            'enabled' => true,
            'secret' => 'new-secret',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/webhooks/hook-123/rotate-secret');
        });

        $api = new WebhooksApi($http);
        $result = $api->rotateSecret('hook-123');

        expect($result)->toBeInstanceOf(Webhook::class);
        expect($result->secret)->toBe('new-secret');
    });
});
