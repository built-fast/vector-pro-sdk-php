<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\Api\Account\ApiKeysApi;
use VectorPro\Api\Account\SecretsApi;
use VectorPro\Api\Account\SshKeysApi;
use VectorPro\HttpClient;

final class AccountApi
{
    private const BASE_PATH = '/api/v1/vector/account';

    public readonly SshKeysApi $sshKeys;

    public readonly ApiKeysApi $apiKeys;

    public readonly SecretsApi $secrets;

    public function __construct(
        private readonly HttpClient $http,
    ) {
        $this->sshKeys = new SshKeysApi($http);
        $this->apiKeys = new ApiKeysApi($http);
        $this->secrets = new SecretsApi($http);
    }

    /**
     * Get account summary.
     *
     * @return array<string, mixed>
     */
    public function getSummary(): array
    {
        return $this->http->get(self::BASE_PATH);
    }
}
