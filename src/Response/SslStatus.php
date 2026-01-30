<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class SslStatus
{
    public function __construct(
        public string $id,
        public ?string $status = null,
        public ?string $expires_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'] ?? null,
            expires_at: $data['expires_at'] ?? null,
        );
    }
}
