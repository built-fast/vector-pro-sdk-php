<?php

declare(strict_types=1);

namespace VectorPro\Api\Sites;

use VectorPro\HttpClient;

final class WafApi
{
    private const BASE_PATH = '/api/v1/vector/sites';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List allowed referrers.
     *
     * @return array<string, mixed>
     */
    public function listAllowedReferrers(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/waf/allowed-referrers");
    }

    /**
     * Add an allowed referrer.
     *
     * @param  array{hostname: string}  $data
     * @return array<string, mixed>
     */
    public function addAllowedReferrer(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/waf/allowed-referrers", $data);
    }

    /**
     * Remove an allowed referrer.
     *
     * @return array<string, mixed>
     */
    public function removeAllowedReferrer(string $siteId, string $hostname): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/waf/allowed-referrers/{$hostname}");
    }

    /**
     * List blocked referrers.
     *
     * @return array<string, mixed>
     */
    public function listBlockedReferrers(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/waf/blocked-referrers");
    }

    /**
     * Add a blocked referrer.
     *
     * @param  array{hostname: string}  $data
     * @return array<string, mixed>
     */
    public function addBlockedReferrer(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/waf/blocked-referrers", $data);
    }

    /**
     * Remove a blocked referrer.
     *
     * @return array<string, mixed>
     */
    public function removeBlockedReferrer(string $siteId, string $hostname): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/waf/blocked-referrers/{$hostname}");
    }

    /**
     * List blocked IPs.
     *
     * @return array<string, mixed>
     */
    public function listBlockedIPs(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/waf/blocked-ips");
    }

    /**
     * Add a blocked IP.
     *
     * @param  array{ip: string}  $data
     * @return array<string, mixed>
     */
    public function addBlockedIP(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/waf/blocked-ips", $data);
    }

    /**
     * Remove a blocked IP.
     *
     * @return array<string, mixed>
     */
    public function removeBlockedIP(string $siteId, string $ip): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/waf/blocked-ips/{$ip}");
    }

    /**
     * List rate limit rules.
     *
     * @return array<string, mixed>
     */
    public function listRateLimits(string $siteId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/waf/rate-limits");
    }

    /**
     * Create a rate limit rule.
     *
     * @param  array{path: string, requests_per_second: int, block_duration?: int}  $data
     * @return array<string, mixed>
     */
    public function createRateLimit(string $siteId, array $data): array
    {
        return $this->http->post(self::BASE_PATH."/{$siteId}/waf/rate-limits", $data);
    }

    /**
     * Get a rate limit rule.
     *
     * @return array<string, mixed>
     */
    public function getRateLimit(string $siteId, string $ruleId): array
    {
        return $this->http->get(self::BASE_PATH."/{$siteId}/waf/rate-limits/{$ruleId}");
    }

    /**
     * Update a rate limit rule.
     *
     * @param  array{path?: string, requests_per_second?: int, block_duration?: int}  $data
     * @return array<string, mixed>
     */
    public function updateRateLimit(string $siteId, string $ruleId, array $data): array
    {
        return $this->http->put(self::BASE_PATH."/{$siteId}/waf/rate-limits/{$ruleId}", $data);
    }

    /**
     * Delete a rate limit rule.
     *
     * @return array<string, mixed>
     */
    public function deleteRateLimit(string $siteId, string $ruleId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$siteId}/waf/rate-limits/{$ruleId}");
    }
}
