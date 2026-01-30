<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class Environment
{
    public function __construct(
        public string $id,
        public string $name,
        public string $php_version,
        public bool $is_production = false,
        public ?string $custom_domain = null,
        public ?string $url = null,
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
            php_version: $data['php_version'],
            is_production: $data['is_production'] ?? false,
            custom_domain: $data['custom_domain'] ?? null,
            url: $data['url'] ?? null,
            created_at: $data['created_at'] ?? null,
        );
    }
}
