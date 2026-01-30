<?php

declare(strict_types=1);

namespace VectorPro\Response;

/**
 * @template T
 */
readonly class PaginatedResponse
{
    /**
     * @param  T[]  $data
     */
    public function __construct(
        public array $data,
        public int $current_page = 1,
        public int $per_page = 15,
        public int $total = 0,
        public int $last_page = 1,
    ) {}

    /**
     * @template TItem
     *
     * @param  array<string, mixed>  $response
     * @param  callable(array<string, mixed>): TItem  $itemFactory
     * @return self<TItem>
     */
    public static function fromArray(array $response, callable $itemFactory): self
    {
        $data = array_map($itemFactory, $response['data'] ?? []);
        $meta = $response['meta'] ?? [];

        return new self(
            data: $data,
            current_page: $meta['current_page'] ?? 1,
            per_page: $meta['per_page'] ?? 15,
            total: $meta['total'] ?? count($data),
            last_page: $meta['last_page'] ?? 1,
        );
    }
}
