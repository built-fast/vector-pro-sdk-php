<?php

declare(strict_types=1);

namespace VectorPro\Response;

readonly class Site
{
    /**
     * @param  string[]  $tags
     */
    public function __construct(
        public string $id,
        public string $partner_customer_id,
        public string $dev_php_version,
        public array $tags = [],
        public ?string $status = null,
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
            partner_customer_id: $data['partner_customer_id'],
            dev_php_version: $data['dev_php_version'],
            tags: $data['tags'] ?? [],
            status: $data['status'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
        );
    }
}
