<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use VectorPro\Sdk\Client;
use VectorPro\Sdk\Exceptions\ClientException;

describe('Client', function () {
    beforeEach(function () {
        $this->mockHttpClient = Mockery::mock(ClientInterface::class);
        $this->mockRequestFactory = Mockery::mock(RequestFactoryInterface::class);
        $this->mockStreamFactory = Mockery::mock(StreamFactoryInterface::class);
        $this->mockRequest = Mockery::mock(RequestInterface::class);
        $this->mockStream = Mockery::mock(StreamInterface::class);
    });

    it('can be instantiated', function () {
        $client = new Client('test-api-key');
        expect($client)->toBeInstanceOf(Client::class);
    });

    describe('Sites', function () {
        it('can list sites', function () {
            $responseData = ['data' => [['id' => 'site_123', 'name' => 'My Site']]];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/sites?page=1&per_page=15')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getSites();

            expect($result)->toBe($responseData['data']);
        });

        it('can get a single site', function () {
            $responseData = ['data' => ['id' => 'site_123', 'name' => 'My Site']];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/sites/site_123')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getSite('site_123');

            expect($result)->toBe($responseData['data']);
        });

        it('can create a site', function () {
            $responseData = ['data' => ['id' => 'site_123', 'name' => 'New Site']];
            $requestData = ['name' => 'New Site', 'dev_php_version' => '8.3'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('POST', 'https://api.builtfast.com/api/v1/vector/sites')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);
            setupRequestBody($this, $requestData);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(201, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->createSite($requestData);

            expect($result)->toBe($responseData['data']);
        });

        it('can suspend a site', function () {
            $responseData = ['data' => ['id' => 'site_123', 'status' => 'suspended']];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('PUT', 'https://api.builtfast.com/api/v1/vector/sites/site_123/suspend')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->suspendSite('site_123');

            expect($result)->toBe($responseData['data']);
        });
    });

    describe('Environments', function () {
        it('can list environments', function () {
            $responseData = ['data' => [['id' => 'env_123', 'name' => 'production']]];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/sites/site_123/environments?page=1&per_page=15')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getEnvironments('site_123');

            expect($result)->toBe($responseData['data']);
        });

        it('can create an environment', function () {
            $responseData = ['data' => ['id' => 'env_123', 'name' => 'staging']];
            $requestData = ['name' => 'staging'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('POST', 'https://api.builtfast.com/api/v1/vector/sites/site_123/environments')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);
            setupRequestBody($this, $requestData);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(201, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->createEnvironment('site_123', $requestData);

            expect($result)->toBe($responseData['data']);
        });
    });

    describe('Deployments', function () {
        it('can create a deployment', function () {
            $responseData = ['data' => ['id' => 'deploy_123', 'status' => 'pending']];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('POST', 'https://api.builtfast.com/api/v1/vector/sites/site_123/environments/env_123/deployments')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(201, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->createDeployment('site_123', 'env_123');

            expect($result)->toBe($responseData['data']);
        });

        it('can rollback a deployment', function () {
            $responseData = ['data' => ['id' => 'deploy_456', 'status' => 'pending']];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('POST', 'https://api.builtfast.com/api/v1/vector/sites/site_123/environments/env_123/deployments/deploy_123/rollback')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->rollbackDeployment('site_123', 'env_123', 'deploy_123');

            expect($result)->toBe($responseData['data']);
        });
    });

    describe('WAF', function () {
        it('can get blocked IPs', function () {
            $responseData = ['data' => [['id' => 'ip_123', 'ip' => '192.168.1.1']]];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/sites/site_123/waf/blocked-ips')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getWafBlockedIps('site_123');

            expect($result)->toBe($responseData['data']);
        });

        it('can add a blocked IP', function () {
            $responseData = ['data' => ['id' => 'ip_123', 'ip' => '10.0.0.1']];
            $requestData = ['ip' => '10.0.0.1'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('POST', 'https://api.builtfast.com/api/v1/vector/sites/site_123/waf/blocked-ips')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);
            setupRequestBody($this, $requestData);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(201, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->addWafBlockedIp('site_123', $requestData);

            expect($result)->toBe($responseData['data']);
        });
    });

    describe('Error Handling', function () {
        it('throws ClientException on 401', function () {
            $responseData = ['message' => 'Unauthenticated'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(401, [], json_encode($responseData)));

            $client = createClient($this);

            try {
                $client->getSites();
            } catch (ClientException $e) {
                expect($e->getMessage())->toBe('Unauthenticated');
                expect($e->getStatusCode())->toBe(401);
                expect($e->isAuthenticationError())->toBeTrue();

                return;
            }

            $this->fail('Expected ClientException was not thrown');
        });

        it('throws ClientException on 403', function () {
            $responseData = ['message' => 'Forbidden'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(403, [], json_encode($responseData)));

            $client = createClient($this);

            try {
                $client->getSite('site_123');
            } catch (ClientException $e) {
                expect($e->getMessage())->toBe('Forbidden');
                expect($e->getStatusCode())->toBe(403);
                expect($e->isAuthorizationError())->toBeTrue();

                return;
            }

            $this->fail('Expected ClientException was not thrown');
        });

        it('throws ClientException on 404', function () {
            $responseData = ['message' => 'Site not found'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(404, [], json_encode($responseData)));

            $client = createClient($this);

            try {
                $client->getSite('site_nonexistent');
            } catch (ClientException $e) {
                expect($e->getMessage())->toBe('Site not found');
                expect($e->getStatusCode())->toBe(404);
                expect($e->isNotFoundError())->toBeTrue();

                return;
            }

            $this->fail('Expected ClientException was not thrown');
        });

        it('throws ClientException on 422 with validation errors', function () {
            $responseData = [
                'message' => 'Validation failed',
                'errors' => [
                    'name' => ['The name field is required'],
                    'php_version' => ['The php_version must be valid'],
                ],
            ];
            $requestData = ['name' => ''];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);
            setupRequestBody($this, $requestData);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(422, [], json_encode($responseData)));

            $client = createClient($this);

            try {
                $client->createSite($requestData);
            } catch (ClientException $e) {
                expect($e->getMessage())->toBe('Validation failed');
                expect($e->getStatusCode())->toBe(422);
                expect($e->isValidationError())->toBeTrue();
                expect($e->getValidationErrors())->toHaveKey('name');
                expect($e->firstError())->toBe('The name field is required');
                expect($e->errorsFor('name'))->toBe(['The name field is required']);
                expect($e->hasErrorFor('name'))->toBeTrue();
                expect($e->hasErrorFor('nonexistent'))->toBeFalse();

                return;
            }

            $this->fail('Expected ClientException was not thrown');
        });

        it('throws ClientException on 500', function () {
            $responseData = ['message' => 'Internal server error'];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(500, [], json_encode($responseData)));

            $client = createClient($this);

            try {
                $client->getSites();
            } catch (ClientException $e) {
                expect($e->getMessage())->toBe('Internal server error');
                expect($e->getStatusCode())->toBe(500);
                expect($e->isServerError())->toBeTrue();

                return;
            }

            $this->fail('Expected ClientException was not thrown');
        });

        it('throws ClientException on invalid JSON', function () {
            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], 'not valid json'));

            $client = createClient($this);

            $client->getSites();
        })->throws(ClientException::class, 'Invalid JSON response from API');

        it('returns empty array for empty response body', function () {
            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(204, [], ''));

            $client = createClient($this);

            $result = $client->deleteSite('site_123');

            expect($result)->toBe([]);
        });
    });

    describe('Read-only endpoints', function () {
        it('can get PHP versions', function () {
            $responseData = ['data' => ['8.1', '8.2', '8.3']];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/php-versions')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getPhpVersions();

            expect($result)->toBe($responseData['data']);
        });

        it('can get events with pagination', function () {
            $responseData = ['data' => [['id' => 'event_123', 'type' => 'site.created']]];

            $this->mockRequestFactory->shouldReceive('createRequest')
                ->once()
                ->with('GET', 'https://api.builtfast.com/api/v1/vector/events?page=2&per_page=25')
                ->andReturn($this->mockRequest);

            setupRequestHeaders($this->mockRequest);

            $this->mockHttpClient->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new Response(200, [], json_encode($responseData)));

            $client = createClient($this);

            $result = $client->getEvents(2, 25);

            expect($result)->toBe($responseData['data']);
        });
    });
});

/**
 * Helper function to create a client with mocked dependencies.
 */
function createClient(object $test): Client
{
    return new Client(
        apiKey: 'test-api-key',
        httpClient: $test->mockHttpClient,
        requestFactory: $test->mockRequestFactory,
        streamFactory: $test->mockStreamFactory
    );
}

/**
 * Helper function to set up common request header expectations.
 */
function setupRequestHeaders(MockInterface $mockRequest): void
{
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
}

/**
 * Helper function to set up request body expectations for POST/PUT requests.
 *
 * @param  array<string, mixed>  $data
 */
function setupRequestBody(object $test, array $data): void
{
    $json = json_encode($data, JSON_THROW_ON_ERROR);

    $test->mockStreamFactory->shouldReceive('createStream')
        ->once()
        ->with($json)
        ->andReturn($test->mockStream);

    $test->mockRequest->shouldReceive('withBody')
        ->once()
        ->with($test->mockStream)
        ->andReturnSelf();
}
