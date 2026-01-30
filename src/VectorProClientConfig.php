<?php

declare(strict_types=1);

namespace VectorPro;

final readonly class VectorProClientConfig
{
    public function __construct(
        public string $apiKey,
        public string $baseUrl = 'https://api.builtfast.com',
    ) {}
}
