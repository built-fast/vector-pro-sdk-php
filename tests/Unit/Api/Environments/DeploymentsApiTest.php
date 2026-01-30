<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Environments\DeploymentsApi;

describe('DeploymentsApi', function () {
    it('lists deployments', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments');
        });

        $api = new DeploymentsApi($http);
        $api->list('site-123', 'env-456');
    });

    it('lists deployments with pagination', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('per_page=10&page=2');
        });

        $api = new DeploymentsApi($http);
        $api->list('site-123', 'env-456', ['per_page' => 10, 'page' => 2]);
    });

    it('creates a deployment', function () {
        $http = createHttpClient(['id' => 'deploy-789'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['description'])->toBe('Release v1.0');
        });

        $api = new DeploymentsApi($http);
        $api->create('site-123', 'env-456', ['description' => 'Release v1.0']);
    });

    it('gets a deployment', function () {
        $http = createHttpClient(['id' => 'deploy-789'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments/deploy-789');
        });

        $api = new DeploymentsApi($http);
        $api->get('site-123', 'env-456', 'deploy-789');
    });

    it('rolls back to a deployment', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/rollback');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['deployment_id'])->toBe('prev-deploy');
        });

        $api = new DeploymentsApi($http);
        $api->rollback('site-123', 'env-456', ['deployment_id' => 'prev-deploy']);
    });
});
