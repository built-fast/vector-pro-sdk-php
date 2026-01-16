<?php

declare(strict_types=1);

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use VectorPro\Sdk\Client;

it('can be instantiated', function () {
    $client = new Client('test-api-key');
    expect($client)->toBeInstanceOf(Client::class);
});

it('makes a request with correct headers and body', function () {
    // Mocks
    $mockHttpClient = Mockery::mock(ClientInterface::class);
    $mockRequestFactory = Mockery::mock(RequestFactoryInterface::class);
    $mockStreamFactory = Mockery::mock(StreamFactoryInterface::class);

    $mockRequest = Mockery::mock(RequestInterface::class);
    $mockResponse = Mockery::mock(ResponseInterface::class);
    $mockStream = Mockery::mock(StreamInterface::class);

    // Expectations
    $mockRequestFactory->shouldReceive('createRequest')
        ->once()
        ->with('POST', 'https://api.vector.pro/test-path')
        ->andReturn($mockRequest);

    $mockRequest->shouldReceive('withHeader')
        ->with('Authorization', 'Bearer test-api-key')
        ->once()
        ->andReturnSelf();

    $mockRequest->shouldReceive('withHeader')
        ->with('Content-Type', 'application/json')
        ->once()
        ->andReturnSelf();

    $mockRequest->shouldReceive('withHeader')
        ->with('Accept', 'application/json')
        ->once()
        ->andReturnSelf();

    $data = ['foo' => 'bar'];
    $json = json_encode($data, JSON_THROW_ON_ERROR);

    $mockStreamFactory->shouldReceive('createStream')
        ->once()
        ->with($json)
        ->andReturn($mockStream);

    $mockRequest->shouldReceive('withBody')
        ->once()
        ->with($mockStream)
        ->andReturnSelf();

    $mockHttpClient->shouldReceive('sendRequest')
        ->once()
        ->with($mockRequest)
        ->andReturn($mockResponse);

    // Act
    $client = new Client(
        apiKey: 'test-api-key',
        httpClient: $mockHttpClient,
        requestFactory: $mockRequestFactory,
        streamFactory: $mockStreamFactory
    );

    $response = $client->request('POST', 'test-path', $data);

    expect($response)->toBe($mockResponse);
});
