<?php

declare(strict_types=1);

namespace VectorPro\Sdk;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use JsonException;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use VectorPro\Sdk\Exceptions\ClientException;

final class Client implements ClientInterface
{
    private const DEFAULT_BASE_URL = 'https://api.builtfast.com';

    private HttpClientInterface $httpClient;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = self::DEFAULT_BASE_URL,
        ?HttpClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
    }

    // =========================================================================
    // Sites (10 methods)
    // =========================================================================

    /**
     * List all sites.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSites(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/sites', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a single site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSite(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}");
    }

    /**
     * Create a new site.
     *
     * @param  array<string, mixed>  $data  Site data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createSite(array $data): array
    {
        return $this->request('POST', '/api/v1/vector/sites', $data);
    }

    /**
     * Update a site.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Site data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateSite(string $siteId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}", $data);
    }

    /**
     * Delete a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteSite(string $siteId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}");
    }

    /**
     * Suspend a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function suspendSite(string $siteId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/suspend");
    }

    /**
     * Unsuspend a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function unsuspendSite(string $siteId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/unsuspend");
    }

    /**
     * Reset a site's SFTP password.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetSiteSftpPassword(string $siteId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/reset-sftp-password");
    }

    /**
     * Reset a site's database password.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetSiteDatabasePassword(string $siteId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/reset-database-password");
    }

    /**
     * Purge a site's CDN cache.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Cache purge options
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function purgeSiteCache(string $siteId, array $data = []): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/purge-cache", $data);
    }

    // =========================================================================
    // Environments (8 methods)
    // =========================================================================

    /**
     * List all environments for a site.
     *
     * @param  string  $siteId  The site ID
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEnvironments(string $siteId, int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments", [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a single environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEnvironment(string $siteId, string $environmentId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}");
    }

    /**
     * Create a new environment.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Environment data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createEnvironment(string $siteId, array $data): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/environments", $data);
    }

    /**
     * Update an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  array<string, mixed>  $data  Environment data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateEnvironment(string $siteId, string $environmentId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}", $data);
    }

    /**
     * Delete an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteEnvironment(string $siteId, string $environmentId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}");
    }

    /**
     * Suspend an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function suspendEnvironment(string $siteId, string $environmentId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/suspend");
    }

    /**
     * Unsuspend an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function unsuspendEnvironment(string $siteId, string $environmentId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/unsuspend");
    }

    /**
     * Reset an environment's database password.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetEnvironmentDatabasePassword(string $siteId, string $environmentId): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/reset-database-password");
    }

    // =========================================================================
    // Deployments (4 methods)
    // =========================================================================

    /**
     * List all deployments for an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getDeployments(string $siteId, string $environmentId, int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/deployments", [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a single deployment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  string  $deploymentId  The deployment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getDeployment(string $siteId, string $environmentId, string $deploymentId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/deployments/{$deploymentId}");
    }

    /**
     * Create a new deployment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createDeployment(string $siteId, string $environmentId): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/deployments");
    }

    /**
     * Rollback to a previous deployment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  string  $deploymentId  The deployment ID to rollback to
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function rollbackDeployment(string $siteId, string $environmentId, string $deploymentId): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/deployments/{$deploymentId}/rollback");
    }

    // =========================================================================
    // SSL (2 methods)
    // =========================================================================

    /**
     * Get SSL status for an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSslStatus(string $siteId, string $environmentId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/ssl");
    }

    /**
     * Nudge SSL certificate renewal.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function nudgeSsl(string $siteId, string $environmentId): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/ssl/nudge");
    }

    // =========================================================================
    // Environment Secrets (4 methods)
    // =========================================================================

    /**
     * List all secrets for an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEnvironmentSecrets(string $siteId, string $environmentId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/secrets");
    }

    /**
     * Create a new environment secret.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createEnvironmentSecret(string $siteId, string $environmentId, array $data): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/secrets", $data);
    }

    /**
     * Update an environment secret.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  string  $secretId  The secret ID
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateEnvironmentSecret(string $siteId, string $environmentId, string $secretId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/secrets/{$secretId}", $data);
    }

    /**
     * Delete an environment secret.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @param  string  $secretId  The secret ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteEnvironmentSecret(string $siteId, string $environmentId, string $secretId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/environments/{$environmentId}/secrets/{$secretId}");
    }

    // =========================================================================
    // Global Secrets (4 methods)
    // =========================================================================

    /**
     * List all global secrets.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getGlobalSecrets(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/secrets', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Create a new global secret.
     *
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createGlobalSecret(array $data): array
    {
        return $this->request('POST', '/api/v1/vector/secrets', $data);
    }

    /**
     * Update a global secret.
     *
     * @param  string  $secretId  The secret ID
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateGlobalSecret(string $secretId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/secrets/{$secretId}", $data);
    }

    /**
     * Delete a global secret.
     *
     * @param  string  $secretId  The secret ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteGlobalSecret(string $secretId): array
    {
        return $this->request('DELETE', "/api/v1/vector/secrets/{$secretId}");
    }

    // =========================================================================
    // API Keys (3 methods)
    // =========================================================================

    /**
     * List all API keys.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getApiKeys(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/api-keys', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Create a new API key.
     *
     * @param  array<string, mixed>  $data  API key data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createApiKey(array $data): array
    {
        return $this->request('POST', '/api/v1/vector/api-keys', $data);
    }

    /**
     * Delete an API key.
     *
     * @param  string  $apiKeyId  The API key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteApiKey(string $apiKeyId): array
    {
        return $this->request('DELETE', "/api/v1/vector/api-keys/{$apiKeyId}");
    }

    // =========================================================================
    // SSH Keys - Account (4 methods)
    // =========================================================================

    /**
     * List all account SSH keys.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSshKeys(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/ssh-keys', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a single account SSH key.
     *
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSshKey(string $sshKeyId): array
    {
        return $this->request('GET', "/api/v1/vector/ssh-keys/{$sshKeyId}");
    }

    /**
     * Create a new account SSH key.
     *
     * @param  array<string, mixed>  $data  SSH key data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createSshKey(array $data): array
    {
        return $this->request('POST', '/api/v1/vector/ssh-keys', $data);
    }

    /**
     * Delete an account SSH key.
     *
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteSshKey(string $sshKeyId): array
    {
        return $this->request('DELETE', "/api/v1/vector/ssh-keys/{$sshKeyId}");
    }

    // =========================================================================
    // SSH Keys - Site (3 methods)
    // =========================================================================

    /**
     * List all SSH keys attached to a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSiteSshKeys(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/ssh-keys");
    }

    /**
     * Attach an SSH key to a site.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addSiteSshKey(string $siteId, string $sshKeyId): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/ssh-keys/{$sshKeyId}");
    }

    /**
     * Remove an SSH key from a site.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeSiteSshKey(string $siteId, string $sshKeyId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/ssh-keys/{$sshKeyId}");
    }

    // =========================================================================
    // Webhooks (6 methods)
    // =========================================================================

    /**
     * List all webhooks.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWebhooks(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/webhooks', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a single webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWebhook(string $webhookId): array
    {
        return $this->request('GET', "/api/v1/vector/webhooks/{$webhookId}");
    }

    /**
     * Create a new webhook.
     *
     * @param  array<string, mixed>  $data  Webhook data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createWebhook(array $data): array
    {
        return $this->request('POST', '/api/v1/vector/webhooks', $data);
    }

    /**
     * Update a webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @param  array<string, mixed>  $data  Webhook data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateWebhook(string $webhookId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/webhooks/{$webhookId}", $data);
    }

    /**
     * Delete a webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteWebhook(string $webhookId): array
    {
        return $this->request('DELETE', "/api/v1/vector/webhooks/{$webhookId}");
    }

    /**
     * Rotate a webhook's secret.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function rotateWebhookSecret(string $webhookId): array
    {
        return $this->request('POST', "/api/v1/vector/webhooks/{$webhookId}/rotate-secret");
    }

    // =========================================================================
    // WAF - Allowed Referrers (3 methods)
    // =========================================================================

    /**
     * List all WAF allowed referrers for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafAllowedReferrers(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/waf/allowed-referrers");
    }

    /**
     * Add a WAF allowed referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Referrer data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafAllowedReferrer(string $siteId, array $data): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/waf/allowed-referrers", $data);
    }

    /**
     * Remove a WAF allowed referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $referrerId  The referrer ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafAllowedReferrer(string $siteId, string $referrerId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/waf/allowed-referrers/{$referrerId}");
    }

    // =========================================================================
    // WAF - Blocked Referrers (3 methods)
    // =========================================================================

    /**
     * List all WAF blocked referrers for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafBlockedReferrers(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/waf/blocked-referrers");
    }

    /**
     * Add a WAF blocked referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Referrer data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafBlockedReferrer(string $siteId, array $data): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/waf/blocked-referrers", $data);
    }

    /**
     * Remove a WAF blocked referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $referrerId  The referrer ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafBlockedReferrer(string $siteId, string $referrerId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/waf/blocked-referrers/{$referrerId}");
    }

    // =========================================================================
    // WAF - Blocked IPs (3 methods)
    // =========================================================================

    /**
     * List all WAF blocked IPs for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafBlockedIps(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/waf/blocked-ips");
    }

    /**
     * Add a WAF blocked IP.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  IP data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafBlockedIp(string $siteId, array $data): array
    {
        return $this->request('POST', "/api/v1/vector/sites/{$siteId}/waf/blocked-ips", $data);
    }

    /**
     * Remove a WAF blocked IP.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $blockedIpId  The blocked IP ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafBlockedIp(string $siteId, string $blockedIpId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/waf/blocked-ips/{$blockedIpId}");
    }

    // =========================================================================
    // WAF - Rate Limits (3 methods)
    // =========================================================================

    /**
     * List all WAF rate limits for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafRateLimits(string $siteId): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/waf/rate-limits");
    }

    /**
     * Set WAF rate limits.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Rate limit data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function setWafRateLimits(string $siteId, array $data): array
    {
        return $this->request('PUT', "/api/v1/vector/sites/{$siteId}/waf/rate-limits", $data);
    }

    /**
     * Delete a WAF rate limit.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $rateLimitId  The rate limit ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteWafRateLimit(string $siteId, string $rateLimitId): array
    {
        return $this->request('DELETE', "/api/v1/vector/sites/{$siteId}/waf/rate-limits/{$rateLimitId}");
    }

    // =========================================================================
    // Read-only (4 methods)
    // =========================================================================

    /**
     * Get available PHP versions.
     *
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getPhpVersions(): array
    {
        return $this->request('GET', '/api/v1/vector/php-versions');
    }

    /**
     * Get event log.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEvents(int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', '/api/v1/vector/events', [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get site logs.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $params  Query parameters (type, lines, etc.)
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSiteLogs(string $siteId, array $params = []): array
    {
        return $this->request('GET', "/api/v1/vector/sites/{$siteId}/logs", $params);
    }

    /**
     * Get webhook delivery logs.
     *
     * @param  string  $webhookId  The webhook ID
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWebhookLogs(string $webhookId, int $page = 1, int $perPage = 15): array
    {
        return $this->request('GET', "/api/v1/vector/webhooks/{$webhookId}/logs", [
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }

    // =========================================================================
    // HTTP Request Handling
    // =========================================================================

    /**
     * Make an HTTP request to the API.
     *
     * @param  string  $method  HTTP method
     * @param  string  $path  API path
     * @param  array<string, mixed>  $data  Request data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    private function request(string $method, string $path, array $data = []): array
    {
        $uri = $this->baseUrl.'/'.ltrim($path, '/');

        // For GET requests, append data as query string
        if ($method === 'GET' && $data !== []) {
            $uri .= '?'.http_build_query($data);
        }

        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Authorization', 'Bearer '.$this->apiKey)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json');

        // For non-GET requests, send data as JSON body
        if ($method !== 'GET' && $data !== []) {
            $request = $request->withBody(
                $this->streamFactory->createStream(json_encode($data, JSON_THROW_ON_ERROR))
            );
        }

        $response = $this->httpClient->sendRequest($request);
        $statusCode = $response->getStatusCode();
        $responseBody = (string) $response->getBody();

        // Handle empty responses (204 No Content, etc.)
        if ($responseBody === '') {
            return [];
        }

        try {
            /** @var array<string, mixed> $body */
            $body = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ClientException(
                'Invalid JSON response from API',
                $statusCode,
                ['raw_response' => $responseBody]
            );
        }

        $this->throwIfError($statusCode, $body);

        /** @var array<string, mixed> */
        return $body['data'] ?? [];
    }

    /**
     * Throw an exception if the response indicates an error.
     *
     * @param  int  $statusCode  HTTP status code
     * @param  array<string, mixed>  $body  Response body
     *
     * @throws ClientException
     */
    private function throwIfError(int $statusCode, array $body): void
    {
        if ($statusCode >= 200 && $statusCode < 300) {
            return;
        }

        $message = $body['message'] ?? 'An error occurred';

        throw new ClientException($message, $statusCode, $body);
    }
}
