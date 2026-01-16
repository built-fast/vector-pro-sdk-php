<?php

declare(strict_types=1);

namespace App\Commands\Concerns;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Provides pagination options for list commands.
 *
 * @property InputInterface $input
 *
 * @method void addOption(string $name, ?string $shortcut = null, ?int $mode = null, string $description = '', mixed $default = null)
 */
trait HandlesPagination
{
    /**
     * Add pagination options to the command.
     * Call this in the command's configure method.
     */
    protected function addPaginationOptions(): void
    {
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number', '1');
        $this->addOption('per-page', null, InputOption::VALUE_REQUIRED, 'Items per page', '15');
    }

    /**
     * Get the current page number from input.
     */
    protected function getPage(): int
    {
        $page = $this->input->getOption('page');

        return max(1, (int) $page);
    }

    /**
     * Get the items per page from input.
     */
    protected function getPerPage(): int
    {
        $perPage = $this->input->getOption('per-page');

        return max(1, min(100, (int) $perPage));
    }

    /**
     * Build pagination metadata from API response.
     *
     * @param  array<string, mixed>  $response  The raw API response with pagination
     * @return array<string, mixed>
     */
    protected function extractPaginationMeta(array $response): array
    {
        return [
            'current_page' => $response['current_page'] ?? $this->getPage(),
            'per_page' => $response['per_page'] ?? $this->getPerPage(),
            'total' => $response['total'] ?? null,
            'last_page' => $response['last_page'] ?? null,
            'from' => $response['from'] ?? null,
            'to' => $response['to'] ?? null,
        ];
    }
}
