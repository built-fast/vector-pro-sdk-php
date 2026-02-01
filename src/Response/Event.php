<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class Event
{
    public function __construct(
        public string $id,
        public string $event,
        public ?string $model_type = null,
        public ?string $model_id = null,
        public mixed $context = null,
        public ?Actor $actor = null,
        public ?string $occurred_at = null,
        public ?string $created_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            event: $data['event'],
            model_type: $data['model_type'] ?? null,
            model_id: $data['model_id'] ?? null,
            context: $data['context'] ?? null,
            actor: isset($data['actor']) ? Actor::fromArray($data['actor']) : null,
            occurred_at: $data['occurred_at'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
