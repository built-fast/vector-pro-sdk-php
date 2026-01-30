<?php

declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use VectorPro\Api\Account\SshKeysApi;
use VectorPro\Response\SshKey;

describe('Account SshKeysApi', function () {
    it('lists SSH keys', function () {
        $http = createHttpClient([
            'data' => [
                ['id' => 'key-1', 'name' => 'My Key', 'fingerprint' => 'SHA256:abc'],
            ],
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/ssh-keys');
        });

        $api = new SshKeysApi($http);
        $result = $api->list();

        expect($result)->toBeArray();
        expect($result)->toHaveCount(1);
        expect($result[0])->toBeInstanceOf(SshKey::class);
    });

    it('creates an SSH key', function () {
        $http = createHttpClient([
            'id' => 'key-123',
            'name' => 'My Key',
            'public_key' => 'ssh-ed25519 AAAA...',
            'fingerprint' => 'SHA256:abc',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('POST');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/ssh-keys');
            $body = json_decode($request->getBody()->getContents(), true);
            expect($body['name'])->toBe('My Key');
            expect($body['public_key'])->toBe('ssh-ed25519 AAAA...');
        });

        $api = new SshKeysApi($http);
        $result = $api->create(['name' => 'My Key', 'public_key' => 'ssh-ed25519 AAAA...']);

        expect($result)->toBeInstanceOf(SshKey::class);
        expect($result->id)->toBe('key-123');
        expect($result->name)->toBe('My Key');
    });

    it('gets an SSH key', function () {
        $http = createHttpClient([
            'id' => 'key-123',
            'name' => 'My Key',
            'fingerprint' => 'SHA256:abc',
        ], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('GET');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/ssh-keys/key-123');
        });

        $api = new SshKeysApi($http);
        $result = $api->get('key-123');

        expect($result)->toBeInstanceOf(SshKey::class);
        expect($result->id)->toBe('key-123');
    });

    it('deletes an SSH key', function () {
        $http = createHttpClient([], function (RequestInterface $request) {
            expect($request->getMethod())->toBe('DELETE');
            expect($request->getUri()->getPath())->toBe('/api/v1/vector/ssh-keys/key-123');
        });

        $api = new SshKeysApi($http);
        $api->delete('key-123');
    });
});
