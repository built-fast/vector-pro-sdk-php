<?php

declare(strict_types=1);

namespace VectorPro\Response;

readonly class ApiKey
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $token = null,
        public ?string $created_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            token: $data['token'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
