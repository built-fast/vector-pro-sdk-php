<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\AccountApi;

describe('AccountApi', function () {
    it('gets account summary', function () {
        $http = createHttpClient(['name' => 'Test Account'], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/account');
        });

        $api = new AccountApi($http);
        $api->getSummary();
    });
});
