<?php

declare(strict_types=1);

namespace VectorPro\Response;

final readonly class Actor
{
    public function __construct(
        public string $ip,
        public string $token_name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ip: $data['ip'],
            token_name: $data['token_name'],
        );
    }
}
