<?php

declare(strict_types=1);

namespace VectorPro;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class HttpClient
{
    public function __construct(
        private readonly VectorProClientConfig $config,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {}

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>|null  $body
     * @return array<string, mixed>
     */
    public function request(string $method, string $path, array $query = [], ?array $body = null): array
    {
        $uri = $this->config->baseUrl.$path;

        if ($query !== []) {
            $uri .= '?'.http_build_query($query);
        }

        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Authorization', 'Bearer '.$this->config->apiKey)
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json');

        if ($body !== null) {
            $stream = $this->streamFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR));
            $request = $request->withBody($stream);
        }

        $response = $this->httpClient->sendRequest($request);
        $contents = $response->getBody()->getContents();

        if ($contents === '') {
            return [];
        }

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, $query);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, [], $data !== [] ? $data : null);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, [], $data !== [] ? $data : null);
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $path): array
    {
        return $this->request('DELETE', $path);
    }
}
