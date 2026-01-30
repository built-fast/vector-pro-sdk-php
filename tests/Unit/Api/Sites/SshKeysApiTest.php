<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Sites\SshKeysApi;

describe('Sites SshKeysApi', function () {
    it('lists SSH keys for a site', function () {
        $http = createHttpClient(['data' => []], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/ssh-keys');
        });

        $api = new SshKeysApi($http);
        $api->list('site-123');
    });

    it('adds SSH key to site', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/ssh-keys');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['ssh_key_id'])->toBe('key-456');
        });

        $api = new SshKeysApi($http);
        $api->add('site-123', ['ssh_key_id' => 'key-456']);
    });

    it('removes SSH key from site', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/sites/site-123/ssh-keys/key-456');
        });

        $api = new SshKeysApi($http);
        $api->remove('site-123', 'key-456');
    });
});
