<?php

declare(strict_types=1);

namespace VectorPro\Response;

readonly class Webhook
{
    /**
     * @param  string[]  $events
     */
    public function __construct(
        public string $id,
        public string $url,
        public array $events = [],
        public bool $enabled = true,
        public ?string $secret = null,
        public ?string $created_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['url'],
            events: $data['events'] ?? [],
            enabled: $data['enabled'] ?? true,
            secret: $data['secret'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
