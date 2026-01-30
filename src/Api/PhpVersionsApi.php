<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\HttpClient;

final class PhpVersionsApi
{
    private const BASE_PATH = '/api/v1/vector/php-versions';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List available PHP versions.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->http->get(self::BASE_PATH);
    }
}
