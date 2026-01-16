<?php

declare(strict_types=1);

namespace App\Services\Output;

use Illuminate\Support\Arr;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Formats command output for both JSON and human-readable modes.
 */
final class OutputFormatter
{
    /**
     * Output data as JSON.
     *
     * @param  array<string, mixed>|array<int, mixed>  $data
     * @param  array<string, mixed>|null  $meta  Optional metadata (pagination, etc.)
     */
    public function toJson(OutputInterface $output, array $data, ?array $meta = null): void
    {
        $envelope = ['data' => $data];

        if ($meta !== null) {
            $envelope['meta'] = $meta;
        }

        $json = json_encode($envelope, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $output->writeln($json);
    }

    /**
     * Output data as a table.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $headers
     * @param  array<int, string>|null  $columnMap  List of data keys in column order
     */
    public function toTable(OutputInterface $output, array $rows, array $headers, ?array $columnMap = null): void
    {
        $table = new Table($output);
        $table->setHeaders($headers);

        foreach ($rows as $row) {
            if ($columnMap !== null) {
                $tableRow = [];
                foreach ($columnMap as $key) {
                    $tableRow[] = $this->formatValue(Arr::get($row, $key));
                }
                $table->addRow($tableRow);
            } else {
                $table->addRow(array_map(fn ($v) => $this->formatValue($v), array_values($row)));
            }
        }

        $table->render();
    }

    /**
     * Output data as key-value pairs for a single resource.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, string>|null  $labels  Maps data key to display label
     */
    public function toDetail(OutputInterface $output, array $data, ?array $labels = null): void
    {
        $maxLabelLength = 0;
        $displayData = [];

        if ($labels !== null) {
            foreach ($labels as $key => $label) {
                $displayData[$label] = Arr::get($data, $key);
                $maxLabelLength = max($maxLabelLength, mb_strlen($label));
            }
        } else {
            foreach ($data as $key => $value) {
                $label = $this->keyToLabel((string) $key);
                $displayData[$label] = $value;
                $maxLabelLength = max($maxLabelLength, mb_strlen($label));
            }
        }

        foreach ($displayData as $label => $value) {
            $paddedLabel = mb_str_pad($label, $maxLabelLength);
            $formattedValue = $this->formatValue($value);
            $output->writeln("<info>{$paddedLabel}</info>  {$formattedValue}");
        }
    }

    /**
     * Output an error message.
     */
    public function error(OutputInterface $output, string $message): void
    {
        $output->writeln("<error>{$message}</error>");
    }

    /**
     * Output a success message.
     */
    public function success(OutputInterface $output, string $message): void
    {
        $output->writeln("<info>{$message}</info>");
    }

    /**
     * Output a warning message.
     */
    public function warning(OutputInterface $output, string $message): void
    {
        $output->writeln("<comment>{$message}</comment>");
    }

    /**
     * Output pagination info.
     *
     * @param  array<string, mixed>  $meta
     */
    public function paginationInfo(OutputInterface $output, array $meta): void
    {
        $page = $meta['current_page'] ?? $meta['page'] ?? 1;
        $perPage = $meta['per_page'] ?? 15;
        $total = $meta['total'] ?? null;
        $lastPage = $meta['last_page'] ?? null;

        $info = "Page {$page}";
        if ($lastPage !== null) {
            $info .= " of {$lastPage}";
        }
        if ($total !== null) {
            $info .= " ({$total} total)";
        }

        $output->writeln('');
        $output->writeln("<comment>{$info}</comment>");
    }

    /**
     * Format a value for display.
     */
    private function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            if ($value === []) {
                return '-';
            }
            // Simple arrays become comma-separated
            if (array_is_list($value)) {
                return implode(', ', array_map(fn ($v) => $this->formatValue($v), $value));
            }

            // Associative arrays become JSON
            return json_encode($value, JSON_UNESCAPED_SLASHES) ?: '-';
        }

        return (string) $value;
    }

    /**
     * Convert a snake_case key to a human-readable label.
     */
    private function keyToLabel(string $key): string
    {
        return ucfirst(str_replace('_', ' ', $key));
    }
}
