<?php

declare(strict_types=1);

namespace VectorPro\Sdk;

use VectorPro\Sdk\Exceptions\ClientException;

interface ClientInterface
{
    // =========================================================================
    // Sites
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
    public function getSites(int $page = 1, int $perPage = 15): array;

    /**
     * Get a single site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSite(string $siteId): array;

    /**
     * Create a new site.
     *
     * @param  array<string, mixed>  $data  Site data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createSite(array $data): array;

    /**
     * Update a site.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Site data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateSite(string $siteId, array $data): array;

    /**
     * Delete a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteSite(string $siteId): array;

    /**
     * Suspend a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function suspendSite(string $siteId): array;

    /**
     * Unsuspend a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function unsuspendSite(string $siteId): array;

    /**
     * Reset a site's SFTP password.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetSiteSftpPassword(string $siteId): array;

    /**
     * Reset a site's database password.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetSiteDatabasePassword(string $siteId): array;

    /**
     * Purge a site's CDN cache.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Cache purge options
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function purgeSiteCache(string $siteId, array $data = []): array;

    // =========================================================================
    // Environments
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
    public function getEnvironments(string $siteId, int $page = 1, int $perPage = 15): array;

    /**
     * Get a single environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEnvironment(string $siteId, string $environmentId): array;

    /**
     * Create a new environment.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Environment data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createEnvironment(string $siteId, array $data): array;

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
    public function updateEnvironment(string $siteId, string $environmentId, array $data): array;

    /**
     * Delete an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteEnvironment(string $siteId, string $environmentId): array;

    /**
     * Suspend an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function suspendEnvironment(string $siteId, string $environmentId): array;

    /**
     * Unsuspend an environment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function unsuspendEnvironment(string $siteId, string $environmentId): array;

    /**
     * Reset an environment's database password.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function resetEnvironmentDatabasePassword(string $siteId, string $environmentId): array;

    // =========================================================================
    // Deployments
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
    public function getDeployments(string $siteId, string $environmentId, int $page = 1, int $perPage = 15): array;

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
    public function getDeployment(string $siteId, string $environmentId, string $deploymentId): array;

    /**
     * Create a new deployment.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createDeployment(string $siteId, string $environmentId): array;

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
    public function rollbackDeployment(string $siteId, string $environmentId, string $deploymentId): array;

    // =========================================================================
    // SSL
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
    public function getSslStatus(string $siteId, string $environmentId): array;

    /**
     * Nudge SSL certificate renewal.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $environmentId  The environment ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function nudgeSsl(string $siteId, string $environmentId): array;

    // =========================================================================
    // Environment Secrets
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
    public function getEnvironmentSecrets(string $siteId, string $environmentId): array;

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
    public function createEnvironmentSecret(string $siteId, string $environmentId, array $data): array;

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
    public function updateEnvironmentSecret(string $siteId, string $environmentId, string $secretId, array $data): array;

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
    public function deleteEnvironmentSecret(string $siteId, string $environmentId, string $secretId): array;

    // =========================================================================
    // Global Secrets
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
    public function getGlobalSecrets(int $page = 1, int $perPage = 15): array;

    /**
     * Create a new global secret.
     *
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createGlobalSecret(array $data): array;

    /**
     * Update a global secret.
     *
     * @param  string  $secretId  The secret ID
     * @param  array<string, mixed>  $data  Secret data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateGlobalSecret(string $secretId, array $data): array;

    /**
     * Delete a global secret.
     *
     * @param  string  $secretId  The secret ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteGlobalSecret(string $secretId): array;

    // =========================================================================
    // API Keys
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
    public function getApiKeys(int $page = 1, int $perPage = 15): array;

    /**
     * Create a new API key.
     *
     * @param  array<string, mixed>  $data  API key data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createApiKey(array $data): array;

    /**
     * Delete an API key.
     *
     * @param  string  $apiKeyId  The API key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteApiKey(string $apiKeyId): array;

    // =========================================================================
    // SSH Keys - Account
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
    public function getSshKeys(int $page = 1, int $perPage = 15): array;

    /**
     * Get a single account SSH key.
     *
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSshKey(string $sshKeyId): array;

    /**
     * Create a new account SSH key.
     *
     * @param  array<string, mixed>  $data  SSH key data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createSshKey(array $data): array;

    /**
     * Delete an account SSH key.
     *
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteSshKey(string $sshKeyId): array;

    // =========================================================================
    // SSH Keys - Site
    // =========================================================================

    /**
     * List all SSH keys attached to a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSiteSshKeys(string $siteId): array;

    /**
     * Attach an SSH key to a site.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addSiteSshKey(string $siteId, string $sshKeyId): array;

    /**
     * Remove an SSH key from a site.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $sshKeyId  The SSH key ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeSiteSshKey(string $siteId, string $sshKeyId): array;

    // =========================================================================
    // Webhooks
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
    public function getWebhooks(int $page = 1, int $perPage = 15): array;

    /**
     * Get a single webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWebhook(string $webhookId): array;

    /**
     * Create a new webhook.
     *
     * @param  array<string, mixed>  $data  Webhook data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function createWebhook(array $data): array;

    /**
     * Update a webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @param  array<string, mixed>  $data  Webhook data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function updateWebhook(string $webhookId, array $data): array;

    /**
     * Delete a webhook.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteWebhook(string $webhookId): array;

    /**
     * Rotate a webhook's secret.
     *
     * @param  string  $webhookId  The webhook ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function rotateWebhookSecret(string $webhookId): array;

    // =========================================================================
    // WAF - Allowed Referrers
    // =========================================================================

    /**
     * List all WAF allowed referrers for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafAllowedReferrers(string $siteId): array;

    /**
     * Add a WAF allowed referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Referrer data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafAllowedReferrer(string $siteId, array $data): array;

    /**
     * Remove a WAF allowed referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $referrerId  The referrer ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafAllowedReferrer(string $siteId, string $referrerId): array;

    // =========================================================================
    // WAF - Blocked Referrers
    // =========================================================================

    /**
     * List all WAF blocked referrers for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafBlockedReferrers(string $siteId): array;

    /**
     * Add a WAF blocked referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Referrer data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafBlockedReferrer(string $siteId, array $data): array;

    /**
     * Remove a WAF blocked referrer.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $referrerId  The referrer ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafBlockedReferrer(string $siteId, string $referrerId): array;

    // =========================================================================
    // WAF - Blocked IPs
    // =========================================================================

    /**
     * List all WAF blocked IPs for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafBlockedIps(string $siteId): array;

    /**
     * Add a WAF blocked IP.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  IP data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function addWafBlockedIp(string $siteId, array $data): array;

    /**
     * Remove a WAF blocked IP.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $blockedIpId  The blocked IP ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function removeWafBlockedIp(string $siteId, string $blockedIpId): array;

    // =========================================================================
    // WAF - Rate Limits
    // =========================================================================

    /**
     * List all WAF rate limits for a site.
     *
     * @param  string  $siteId  The site ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getWafRateLimits(string $siteId): array;

    /**
     * Set WAF rate limits.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $data  Rate limit data
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function setWafRateLimits(string $siteId, array $data): array;

    /**
     * Delete a WAF rate limit.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $rateLimitId  The rate limit ID
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function deleteWafRateLimit(string $siteId, string $rateLimitId): array;

    // =========================================================================
    // Read-only
    // =========================================================================

    /**
     * Get available PHP versions.
     *
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getPhpVersions(): array;

    /**
     * Get event log.
     *
     * @param  int  $page  Page number
     * @param  int  $perPage  Items per page
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getEvents(int $page = 1, int $perPage = 15): array;

    /**
     * Get site logs.
     *
     * @param  string  $siteId  The site ID
     * @param  array<string, mixed>  $params  Query parameters (type, lines, etc.)
     * @return array<string, mixed>
     *
     * @throws ClientException
     */
    public function getSiteLogs(string $siteId, array $params = []): array;

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
    public function getWebhookLogs(string $webhookId, int $page = 1, int $perPage = 15): array;
}
