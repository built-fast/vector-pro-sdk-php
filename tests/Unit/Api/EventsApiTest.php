<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\EventsApi;
use VectorPro\Response\Actor;
use VectorPro\Response\Event;
use VectorPro\Response\PaginatedResponse;

describe('EventsApi', function () {
    it('lists events', function () {
        $http = createHttpClient([
            'data' => [
                [
                    'id' => 'event-1',
                    'event' => 'site.created',
                    'model_type' => 'site',
                    'model_id' => 'site-123',
                    'actor' => ['ip' => '192.168.1.1', 'token_name' => 'My API Key'],
                    'occurred_at' => '2024-01-15T10:30:00Z',
                ],
            ],
            'meta' => ['current_page' => 1, 'per_page' => 15, 'total' => 1, 'last_page' => 1],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/events');
        });

        $api = new EventsApi($http);
        $result = $api->list();

        expect($result)->toBeInstanceOf(PaginatedResponse::class);
        expect($result->data)->toHaveCount(1);
        expect($result->data[0])->toBeInstanceOf(Event::class);
        expect($result->data[0]->id)->toBe('event-1');
        expect($result->data[0]->event)->toBe('site.created');
        expect($result->data[0]->model_type)->toBe('site');
        expect($result->data[0]->model_id)->toBe('site-123');
        expect($result->data[0]->actor)->toBeInstanceOf(Actor::class);
        expect($result->data[0]->actor->ip)->toBe('192.168.1.1');
        expect($result->data[0]->actor->token_name)->toBe('My API Key');
    });

    it('lists events with null actor', function () {
        $http = createHttpClient([
            'data' => [
                [
                    'id' => 'event-2',
                    'event' => 'system.maintenance',
                    'actor' => null,
                ],
            ],
            'meta' => ['current_page' => 1, 'per_page' => 15, 'total' => 1, 'last_page' => 1],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
        });

        $api = new EventsApi($http);
        $result = $api->list();

        expect($result->data[0]->actor)->toBeNull();
    });

    it('lists events with filters', function () {
        $http = createHttpClient([
            'data' => [],
            'meta' => ['current_page' => 1, 'per_page' => 50, 'total' => 0, 'last_page' => 1],
        ], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('site_id=site-123&type=deployment&per_page=50');
        });

        $api = new EventsApi($http);
        $api->list(['site_id' => 'site-123', 'type' => 'deployment', 'per_page' => 50]);
    });
});
