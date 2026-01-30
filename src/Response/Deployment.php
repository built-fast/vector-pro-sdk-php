<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class Deployment
{
    public function __construct(
        public string $id,
        public ?string $description = null,
        public ?string $status = null,
        public ?string $created_at = null,
        public ?string $completed_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            description: $data['description'] ?? null,
            status: $data['status'] ?? null,
            created_at: $data['created_at'] ?? null,
            completed_at: $data['completed_at'] ?? null,
        );
    }
}
