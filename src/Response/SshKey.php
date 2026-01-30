<?php

declare(strict_types=1);

namespace VectorPro\Response;

readonly class SshKey
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $public_key = null,
        public ?string $fingerprint = null,
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
            public_key: $data['public_key'] ?? null,
            fingerprint: $data['fingerprint'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
