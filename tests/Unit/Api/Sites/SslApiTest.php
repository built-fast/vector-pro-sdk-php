<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\SslApi;
use VectorPro\Response\SslStatus;

describe('SslApi', function () {
    it('gets SSL status', function () {
        $http = createHttpClient([
            'id' => 'ssl-123',
            'status' => 'active',
            'expires_at' => '2025-12-31T23:59:59Z',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/environments/env-456/ssl');
        });

        $api = new SslApi($http);
        $result = $api->getStatus('env-456');

        expect($result)->toBeInstanceOf(SslStatus::class);
        expect($result->status)->toBe('active');
        expect($result->expires_at)->toBe('2025-12-31T23:59:59Z');
    });

    it('nudges SSL provisioning', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/environments/env-456/ssl/nudge');
        });

        $api = new SslApi($http);
        $api->nudge('env-456');
    });
});
