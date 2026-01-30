<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class ExportStatus
{
    public function __construct(
        public string $id,
        public ?string $status = null,
        public ?string $download_url = null,
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
            download_url: $data['download_url'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
