<?php

declare(strict_types=1);

namespace VectorPro\Api;

use VectorPro\HttpClient;
use VectorPro\Response\Event;
use VectorPro\Response\PaginatedResponse;

final class EventsApi
{
    private const BASE_PATH = '/api/v1/vector/events';

    public function __construct(
        private readonly HttpClient $http,
    ) {}

    /**
     * List events.
     *
     * @param  array{per_page?: int, page?: int, site_id?: string, type?: string}  $options
     * @return PaginatedResponse<Event>
     */
    public function list(array $options = []): PaginatedResponse
    {
        $response = $this->http->get(self::BASE_PATH, $options);

        return PaginatedResponse::fromArray($response, Event::fromArray(...));
    }
}
