<?php

declare(strict_types=1);

use VectorPro\VectorProClientConfig;

describe('VectorProClientConfig', function () {
    it('stores API key', function () {
        $config = new VectorProClientConfig('my-api-key');

        expect($config->apiKey)->toBe('my-api-key');
    });

    it('uses default base URL', function () {
        $config = new VectorProClientConfig('my-api-key');

        expect($config->baseUrl)->toBe('https://api.builtfast.com');
    });

    it('accepts custom base URL', function () {
        $config = new VectorProClientConfig('my-api-key', 'https://custom.api.com');

        expect($config->baseUrl)->toBe('https://custom.api.com');
    });
});
