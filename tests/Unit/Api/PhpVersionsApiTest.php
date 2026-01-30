<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\PhpVersionsApi;

describe('PhpVersionsApi', function () {
    it('lists PHP versions', function () {
        $http = createHttpClient(['data' => ['8.1', '8.2', '8.3']], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/php-versions');
        });

        $api = new PhpVersionsApi($http);
        $result = $api->list();

        expect($result['data'])->toBe(['8.1', '8.2', '8.3']);
    });
});
