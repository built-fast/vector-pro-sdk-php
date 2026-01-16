<?php

declare(strict_types=1);

namespace App\Commands\Concerns;

use App\Enums\ExitCode;
use App\Services\Config\ConfigRepository;
use Symfony\Component\Console\Output\OutputInterface;
use VectorPro\Sdk\Client;
use VectorPro\Sdk\ClientInterface;

/**
 * Provides authentication handling for commands.
 *
 * @property OutputInterface $output
 */
trait HandlesAuthentication
{
    /**
     * The SDK client instance.
     */
    protected ?ClientInterface $client = null;

    /**
     * Get the SDK client, resolving the API key from environment or config.
     */
    protected function getClient(): ?ClientInterface
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $apiKey = $this->resolveApiKey();

        if ($apiKey === null) {
            return null;
        }

        /** @var ConfigRepository $config */
        $config = app(ConfigRepository::class);

        $this->client = new Client($apiKey, $config->getApiUrl());

        return $this->client;
    }

    /**
     * Require authentication and return the client.
     * Returns null and outputs error message if not authenticated.
     */
    protected function requireAuth(): ?ClientInterface
    {
        $client = $this->getClient();

        if ($client === null) {
            $this->output->writeln('<error>Not authenticated. Run "vector auth login" first or set VECTOR_API_KEY.</error>');

            return null;
        }

        return $client;
    }

    /**
     * Get the exit code for authentication errors.
     */
    protected function authErrorCode(): int
    {
        return ExitCode::AUTH_ERROR->code();
    }

    /**
     * Check if an API key is available from any source.
     */
    protected function hasApiKey(): bool
    {
        return $this->resolveApiKey() !== null;
    }

    /**
     * Check if the API key comes from environment.
     */
    protected function isUsingEnvApiKey(): bool
    {
        $envKey = getenv('VECTOR_API_KEY');

        return $envKey !== false && $envKey !== '';
    }

    /**
     * Resolve the API key from environment or config.
     * Environment variable takes precedence.
     */
    private function resolveApiKey(): ?string
    {
        // Environment variable takes precedence
        $envKey = getenv('VECTOR_API_KEY');
        if ($envKey !== false && $envKey !== '') {
            return $envKey;
        }

        // Fall back to stored config
        /** @var ConfigRepository $config */
        $config = app(ConfigRepository::class);

        return $config->getApiToken();
    }
}
