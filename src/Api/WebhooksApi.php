<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\HttpClient;

final class WebhooksApi
{
    private const BASE_PATH = '/api/v1/vector/webhooks';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List webhooks.
     *
     * @return array<string, mixed>
     */
    public function list(): array
    {
        return $this->http->get(self::BASE_PATH);
    }

    /**
     * Get a webhook.
     *
     * @return array<string, mixed>
     */
    public function get(string $webhookId): array
    {
        return $this->http->get(self::BASE_PATH."/{$webhookId}");
    }

    /**
     * Create a webhook.
     *
     * @param  array{url: string, events: string[], type?: string}  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post(self::BASE_PATH, $data);
    }

    /**
     * Update a webhook.
     *
     * @param  array{url?: string, events?: string[], enabled?: bool}  $data
     * @return array<string, mixed>
     */
    public function update(string $webhookId, array $data): array
    {
        return $this->http->put(self::BASE_PATH."/{$webhookId}", $data);
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
     *
     * @return array<string, mixed>
     */
    public function rotateSecret(string $webhookId): array
    {
        return $this->http->post(self::BASE_PATH."/{$webhookId}/rotate-secret");
    }
}
