<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use Mockery as m;
use Psr\Http\Client\ClientInterface;
use VectorPro\Api\Account\ApiKeysApi;
use VectorPro\Api\Account\SecretsApi;
use VectorPro\Api\AccountApi;
use VectorPro\Api\Environments\DeploymentsApi;
use VectorPro\Api\EnvironmentsApi;
use VectorPro\Api\EventsApi;
use VectorPro\Api\PhpVersionsApi;
use VectorPro\Api\Sites\DatabaseApi;
use VectorPro\Api\Sites\SshKeysApi;
use VectorPro\Api\Sites\SslApi;
use VectorPro\Api\Sites\WafApi;
use VectorPro\Api\SitesApi;
use VectorPro\Api\WebhooksApi;
use VectorPro\VectorProClient;
use VectorPro\VectorProClientConfig;

describe('VectorProClient', function () {
    it('initializes all API instances', function () {
        $config = new VectorProClientConfig('test-key');
        $factory = new HttpFactory;
        $mockClient = m::mock(ClientInterface::class);

        $client = new VectorProClient($config, $mockClient, $factory, $factory);

        expect($client->sites)->toBeInstanceOf(SitesApi::class);
        expect($client->environments)->toBeInstanceOf(EnvironmentsApi::class);
        expect($client->account)->toBeInstanceOf(AccountApi::class);
        expect($client->webhooks)->toBeInstanceOf(WebhooksApi::class);
        expect($client->events)->toBeInstanceOf(EventsApi::class);
        expect($client->phpVersions)->toBeInstanceOf(PhpVersionsApi::class);
    });

    it('exposes sites sub-APIs', function () {
        $config = new VectorProClientConfig('test-key');
        $factory = new HttpFactory;
        $mockClient = m::mock(ClientInterface::class);

        $client = new VectorProClient($config, $mockClient, $factory, $factory);

        expect($client->sites->db)->toBeInstanceOf(DatabaseApi::class);
        expect($client->sites->waf)->toBeInstanceOf(WafApi::class);
        expect($client->sites->sshKeys)->toBeInstanceOf(SshKeysApi::class);
        expect($client->sites->ssl)->toBeInstanceOf(SslApi::class);
    });

    it('exposes account sub-APIs', function () {
        $config = new VectorProClientConfig('test-key');
        $factory = new HttpFactory;
        $mockClient = m::mock(ClientInterface::class);

        $client = new VectorProClient($config, $mockClient, $factory, $factory);

        expect($client->account->sshKeys)->toBeInstanceOf(VectorPro\Api\Account\SshKeysApi::class);
        expect($client->account->apiKeys)->toBeInstanceOf(ApiKeysApi::class);
        expect($client->account->secrets)->toBeInstanceOf(SecretsApi::class);
    });

    it('exposes environments sub-APIs', function () {
        $config = new VectorProClientConfig('test-key');
        $factory = new HttpFactory;
        $mockClient = m::mock(ClientInterface::class);

        $client = new VectorProClient($config, $mockClient, $factory, $factory);

        expect($client->environments->deployments)->toBeInstanceOf(DeploymentsApi::class);
        expect($client->environments->secrets)->toBeInstanceOf(VectorPro\Api\Environments\SecretsApi::class);
    });
});
