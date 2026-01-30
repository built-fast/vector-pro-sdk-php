<?php

declare(strict_types=1);

namespace VectorPro\Api\Sites;

use VectorPro\HttpClient;
use VectorPro\Response\SslStatus;

final class SslApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * Get SSL status for an environment.
     */
    public function getStatus(string $siteId, string $environmentId): SslStatus
    {
        $response = $this->http->get(self::BASE_PATH."/{$siteId}/environments/{$environmentId}/ssl");

        return SslStatus::fromArray($response);
    }

    /**
     * Nudge SSL certificate provisioning for an environment.
     *
     * @return array<string, mixed>
     */
    public function nudge(string $siteId, string $environmentId): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/environments/{$environmentId}/ssl/nudge");
    }
}
