<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Environments\DeploymentsApi;
use VectorPro\Response\Deployment;
use VectorPro\Response\PaginatedResponse;

describe('DeploymentsApi', function () {
    it('lists deployments', function () {
        $http = createHttpClient([
            'data' => [
                ['id' => 'deploy-1', 'description' => 'Initial deploy', 'status' => 'completed'],
            ],
            'meta' => ['current_page' => 1, 'per_page' => 15, 'total' => 1, 'last_page' => 1],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments');
        });

        $api = new DeploymentsApi($http);
        $result = $api->list('site-123', 'env-456');

        expect($result)->toBeInstanceOf(PaginatedResponse::class);
        expect($result->data)->toHaveCount(1);
        expect($result->data[0])->toBeInstanceOf(Deployment::class);
    });

    it('lists deployments with pagination', function () {
        $http = createHttpClient([
            'data' => [],
            'meta' => ['current_page' => 2, 'per_page' => 10, 'total' => 0, 'last_page' => 1],
        ], function (RequestInterface $request) {
            expect($request->getUri()->getQuery())->toBe('per_page=10&page=2');
        });

        $api = new DeploymentsApi($http);
        $result = $api->list('site-123', 'env-456', ['per_page' => 10, 'page' => 2]);

        expect($result->current_page)->toBe(2);
    });

    it('creates a deployment', function () {
        $http = createHttpClient([
            'id' => 'deploy-789',
            'description' => 'Release v1.0',
            'status' => 'pending',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['description'])->toBe('Release v1.0');
        });

        $api = new DeploymentsApi($http);
        $result = $api->create('site-123', 'env-456', ['description' => 'Release v1.0']);

        expect($result)->toBeInstanceOf(Deployment::class);
        expect($result->id)->toBe('deploy-789');
        expect($result->description)->toBe('Release v1.0');
    });

    it('gets a deployment', function () {
        $http = createHttpClient([
            'id' => 'deploy-789',
            'description' => 'Release v1.0',
            'status' => 'completed',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/deployments/deploy-789');
        });

        $api = new DeploymentsApi($http);
        $result = $api->get('site-123', 'env-456', 'deploy-789');

        expect($result)->toBeInstanceOf(Deployment::class);
        expect($result->id)->toBe('deploy-789');
    });

    it('rolls back to a deployment', function () {
        $http = createHttpClient([
            'id' => 'rollback-deploy',
            'description' => 'Rollback to prev-deploy',
            'status' => 'pending',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/environments/env-456/rollback');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['deployment_id'])->toBe('prev-deploy');
        });

        $api = new DeploymentsApi($http);
        $result = $api->rollback('site-123', 'env-456', ['deployment_id' => 'prev-deploy']);

        expect($result)->toBeInstanceOf(Deployment::class);
    });
});
