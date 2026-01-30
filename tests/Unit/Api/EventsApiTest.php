<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\EventsApi;

describe('EventsApi', function () {
    it('lists events', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/events');
        });

        $api = new EventsApi($http);
        $api->list();
    });

    it('lists events with filters', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('site_id=site-123&type=deployment&per_page=50');
        });

        $api = new EventsApi($http);
        $api->list(['site_id' => 'site-123', 'type' => 'deployment', 'per_page' => 50]);
    });
});
