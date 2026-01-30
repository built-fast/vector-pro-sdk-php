<?php

declare(strict_types=1);

namespace VectorPro;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use VectorPro\Api\AccountApi;
use VectorPro\Api\EnvironmentsApi;
use VectorPro\Api\EventsApi;
use VectorPro\Api\PhpVersionsApi;
use VectorPro\Api\SitesApi;
use VectorPro\Api\WebhooksApi;

final class VectorProClient
{
    public readonly SitesApi $sites;

    public readonly EnvironmentsApi $environments;

    public readonly AccountApi $account;

    public readonly WebhooksApi $webhooks;

    public readonly EventsApi $events;

    public readonly PhpVersionsApi $phpVersions;

    public function __construct(
        VectorProClientConfig $config,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
    ) {
        $http = new HttpClient($config, $httpClient, $requestFactory, $streamFactory);

        $this->sites = new SitesApi($http);
        $this->environments = new EnvironmentsApi($http);
        $this->account = new AccountApi($http);
        $this->webhooks = new WebhooksApi($http);
        $this->events = new EventsApi($http);
        $this->phpVersions = new PhpVersionsApi($http);
    }
}
