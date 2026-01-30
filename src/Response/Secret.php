<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class Secret
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }
}
