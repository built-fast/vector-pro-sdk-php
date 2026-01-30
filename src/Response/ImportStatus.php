<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class ImportStatus
{
    public function __construct(
        public string $id,
        public ?string $status = null,
        public ?string $filename = null,
        public ?string $error = null,
        public ?string $upload_url = null,
        public ?string $created_at = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'] ?? null,
            filename: $data['filename'] ?? null,
            error: $data['error'] ?? null,
            upload_url: $data['upload_url'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
