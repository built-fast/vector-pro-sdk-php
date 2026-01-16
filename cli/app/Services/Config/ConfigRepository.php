<?php

declare(strict_types=1);

namespace App\Services\Config;

use JsonException;

/**
 * Manages configuration and credentials storage for the CLI.
 *
 * Configuration is stored in JSON files with secure permissions for credentials.
 */
final class ConfigRepository
{
    private const CREDENTIALS_PERMISSIONS = 0600;

    /**
     * Cached configuration data.
     *
     * @var array<string, mixed>|null
     */
    private ?array $config = null;

    /**
     * Cached credentials data.
     *
     * @var array<string, mixed>|null
     */
    private ?array $credentials = null;

    public function __construct(
        private readonly XdgPathResolver $pathResolver,
    ) {}

    /**
     * Get a configuration value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $config = $this->loadConfig();

        return $config[$key] ?? $default;
    }

    /**
     * Set a configuration value.
     */
    public function set(string $key, mixed $value): void
    {
        $config = $this->loadConfig();
        $config[$key] = $value;
        $this->config = $config;
        $this->saveConfig($config);
    }

    /**
     * Remove a configuration value.
     */
    public function remove(string $key): void
    {
        $config = $this->loadConfig();
        unset($config[$key]);
        $this->config = $config;
        $this->saveConfig($config);
    }

    /**
     * Check if a configuration key exists.
     */
    public function has(string $key): bool
    {
        $config = $this->loadConfig();

        return array_key_exists($key, $config);
    }

    /**
     * Get all configuration values.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->loadConfig();
    }

    /**
     * Get the API token from credentials.
     */
    public function getApiToken(): ?string
    {
        $credentials = $this->loadCredentials();
        $token = $credentials['api_token'] ?? null;

        return is_string($token) ? $token : null;
    }

    /**
     * Set the API token in credentials.
     */
    public function setApiToken(string $token): void
    {
        $credentials = $this->loadCredentials();
        $credentials['api_token'] = $token;
        $this->credentials = $credentials;
        $this->saveCredentials($credentials);
    }

    /**
     * Remove the API token from credentials.
     */
    public function removeApiToken(): void
    {
        $credentials = $this->loadCredentials();
        unset($credentials['api_token']);
        $this->credentials = $credentials;
        $this->saveCredentials($credentials);
    }

    /**
     * Check if an API token is stored.
     */
    public function hasApiToken(): bool
    {
        return $this->getApiToken() !== null;
    }

    /**
     * Get a credential value.
     */
    public function getCredential(string $key): mixed
    {
        $credentials = $this->loadCredentials();

        return $credentials[$key] ?? null;
    }

    /**
     * Set a credential value.
     */
    public function setCredential(string $key, mixed $value): void
    {
        $credentials = $this->loadCredentials();
        $credentials[$key] = $value;
        $this->credentials = $credentials;
        $this->saveCredentials($credentials);
    }

    /**
     * Remove a credential value.
     */
    public function removeCredential(string $key): void
    {
        $credentials = $this->loadCredentials();
        unset($credentials[$key]);
        $this->credentials = $credentials;
        $this->saveCredentials($credentials);
    }

    /**
     * Get the API base URL.
     */
    public function getApiUrl(): string
    {
        $url = $this->get('api_url');

        return is_string($url) ? $url : 'https://api.builtfast.com';
    }

    /**
     * Set the API base URL.
     */
    public function setApiUrl(string $url): void
    {
        $this->set('api_url', $url);
    }

    /**
     * Load configuration from disk.
     *
     * @return array<string, mixed>
     */
    private function loadConfig(): array
    {
        if ($this->config !== null) {
            return $this->config;
        }

        $this->config = $this->loadJsonFile($this->pathResolver->getConfigPath());

        return $this->config;
    }

    /**
     * Load credentials from disk.
     *
     * @return array<string, mixed>
     */
    private function loadCredentials(): array
    {
        if ($this->credentials !== null) {
            return $this->credentials;
        }

        $this->credentials = $this->loadJsonFile($this->pathResolver->getCredentialsPath());

        return $this->credentials;
    }

    /**
     * Load a JSON file.
     *
     * @return array<string, mixed>
     */
    private function loadJsonFile(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return [];
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            return $data;
        } catch (JsonException) {
            return [];
        }
    }

    /**
     * Save configuration to disk.
     *
     * @param  array<string, mixed>  $config
     */
    private function saveConfig(array $config): void
    {
        $this->pathResolver->ensureConfigDirExists();
        $this->saveJsonFile($this->pathResolver->getConfigPath(), $config);
    }

    /**
     * Save credentials to disk with secure permissions.
     *
     * @param  array<string, mixed>  $credentials
     */
    private function saveCredentials(array $credentials): void
    {
        $this->pathResolver->ensureConfigDirExists();
        $path = $this->pathResolver->getCredentialsPath();
        $this->saveJsonFile($path, $credentials);

        // Ensure restrictive permissions on credentials file
        chmod($path, self::CREDENTIALS_PERMISSIONS);
    }

    /**
     * Save a JSON file.
     *
     * @param  array<string, mixed>  $data
     */
    private function saveJsonFile(string $path, array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        file_put_contents($path, $json."\n");
    }
}
