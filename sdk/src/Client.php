<?php

declare(strict_types=1);

namespace VectorPro\Sdk;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Client
{
    private const DEFAULT_BASE_URL = 'https://api.vector.pro';

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = self::DEFAULT_BASE_URL,
        private ?ClientInterface $httpClient = null,
        private ?RequestFactoryInterface $requestFactory = null,
        private ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->httpClient = $httpClient ?: Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function request(string $method, string $path, array $data = []): ResponseInterface
    {
        $uri = $this->baseUrl.'/'.ltrim($path, '/');
        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Authorization', 'Bearer '.$this->apiKey)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json');

        if (! empty($data)) {
            $request = $request->withBody(
                $this->streamFactory->createStream(json_encode($data, JSON_THROW_ON_ERROR))
            );
        }

        return $this->httpClient->sendRequest($request);
    }
}
