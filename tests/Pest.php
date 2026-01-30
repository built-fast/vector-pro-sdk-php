<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use VectorPro\HttpClient;
use VectorPro\Tests\TestCase;
use VectorPro\VectorProClientConfig;

pest()->extend(TestCase::class)->in(__DIR__);

/**
 * Create a mock PSR-18 HTTP client that captures and validates requests.
 *
 * @param  array<string, mixed>  $responseBody
 * @param  callable|null  $requestValidator  Callback to validate the request
 */
function mockHttpClient(array $responseBody = [], ?callable $requestValidator = null, int $statusCode = 200): ClientInterface
{
    $mock = m::mock(ClientInterface::class);
    $mock->shouldReceive('sendRequest')
        ->once()
        ->andReturnUsing(function (RequestInterface $request) use ($responseBody, $requestValidator, $statusCode) {
            if ($requestValidator !== null) {
                $requestValidator($request);
            }

            return new Response(
                $statusCode,
                ['Content-Type' => 'application/json'],
                json_encode($responseBody, JSON_THROW_ON_ERROR)
            );
        });

    return $mock;
}

/**
 * Create an HttpClient instance with a mock PSR-18 client.
 *
 * @param  array<string, mixed>  $responseBody
 */
function createHttpClient(array $responseBody = [], ?callable $requestValidator = null): HttpClient
{
    $config = new VectorProClientConfig('test-api-key');
    $factory = new HttpFactory;
    $mockClient = mockHttpClient($responseBody, $requestValidator);

    return new HttpClient($config, $mockClient, $factory, $factory);
}
