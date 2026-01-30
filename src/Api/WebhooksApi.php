<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\HttpClient;
use VectorPro\Response\Webhook;

final class WebhooksApi
{
    private const BASE_PATH = '/api/v1/vector/webhooks';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List webhooks.
     *
     * @return Webhook[]
     */
    public function list(): array
    {
        $response = $this->http->get(self::BASE_PATH);

        return array_map(Webhook::fromArray(...), $response['data'] ?? $response);
    }

    /**
     * Get a webhook.
     */
    public function get(string $webhookId): Webhook
    {
        $response = $this->http->get(self::BASE_PATH."/{$webhookId}");

        return Webhook::fromArray($response);
    }

    /**
     * Create a webhook.
     *
     * @param  array{url: string, events: string[], type?: string}  $data
     */
    public function create(array $data): Webhook
    {
        $response = $this->http->post(self::BASE_PATH, $data);

        return Webhook::fromArray($response);
    }

    /**
     * Update a webhook.
     *
     * @param  array{url?: string, events?: string[], enabled?: bool}  $data
     */
    public function update(string $webhookId, array $data): Webhook
    {
        $response = $this->http->put(self::BASE_PATH."/{$webhookId}", $data);

        return Webhook::fromArray($response);
    }

    /**
     * Delete a webhook.
     *
     * @return array<string, mixed>
     */
    public function delete(string $webhookId): array
    {
        return $this->http->delete(self::BASE_PATH."/{$webhookId}");
    }

    /**
     * List webhook delivery logs.
     *
     * @param  array{per_page?: int, page?: int}  $options
     * @return array<string, mixed>
     */
    public function listLogs(string $webhookId, array $options = []): array
    {
        return $this->http->get(self::BASE_PATH."/{$webhookId}/logs", $options);
    }

    /**
     * Rotate webhook secret.
     */
    public function rotateSecret(string $webhookId): Webhook
    {
        $response = $this->http->post(self::BASE_PATH."/{$webhookId}/rotate-secret");

        return Webhook::fromArray($response);
    }
}
