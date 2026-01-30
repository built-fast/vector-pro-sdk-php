<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\SslApi;

describe('SslApi', function () {
    it('gets SSL status', function () {
        $http = createHttpClient(['status' => 'active'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/ssl');
        });

        $api = new SslApi($http);
        $api->getStatus('site-123', 'env-456');
    });

    it('nudges SSL provisioning', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/ssl/nudge');
        });

        $api = new SslApi($http);
        $api->nudge('site-123', 'env-456');
    });
});
