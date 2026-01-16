<?php

declare(strict_types=1);

namespace App\Commands\Concerns;

use App\Services\Output\OutputFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides output formatting capabilities for commands.
 *
 * @property OutputInterface $output
 * @property InputInterface $input
 *
 * @method void addOption(string $name, ?string $shortcut = null, ?int $mode = null, string $description = '', mixed $default = null)
 */
trait FormatsOutput
{
    /**
     * The output formatter instance.
     */
    protected ?OutputFormatter $formatter = null;

    /**
     * Add output format options to the command.
     * Call this in the command's configure method.
     */
    protected function addFormatOptions(): void
    {
        $this->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON');
        $this->addOption('no-json', null, InputOption::VALUE_NONE, 'Force human-readable output');
    }

    /**
     * Determine if output should be in JSON format.
     *
     * JSON is used when:
     * - --json flag is provided
     * - stdout is not a TTY (piped/redirected) and --no-json is not set
     */
    protected function shouldOutputJson(): bool
    {
        // Explicit --json flag
        if ($this->input->getOption('json') === true) {
            return true;
        }

        // Explicit --no-json flag
        if ($this->input->getOption('no-json') === true) {
            return false;
        }

        // Auto-detect based on TTY
        return ! $this->isOutputTty();
    }

    /**
     * Check if stdout is a TTY.
     */
    protected function isOutputTty(): bool
    {
        if (! defined('STDOUT')) {
            return true;
        }

        return stream_isatty(STDOUT);
    }

    /**
     * Get the output formatter.
     */
    protected function getFormatter(): OutputFormatter
    {
        if ($this->formatter === null) {
            $this->formatter = app(OutputFormatter::class);
        }

        return $this->formatter;
    }

    /**
     * Output data as JSON.
     *
     * @param  array<string, mixed>|array<int, mixed>  $data
     * @param  array<string, mixed>|null  $meta
     */
    protected function outputJson(array $data, ?array $meta = null): void
    {
        $this->getFormatter()->toJson($this->output, $data, $meta);
    }

    /**
     * Output data as a table.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $headers
     * @param  array<int, string>|null  $columnMap  List of data keys in column order
     */
    protected function outputTable(array $rows, array $headers, ?array $columnMap = null): void
    {
        $this->getFormatter()->toTable($this->output, $rows, $headers, $columnMap);
    }

    /**
     * Output a single resource as key-value pairs.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, string>|null  $labels
     */
    protected function outputDetail(array $data, ?array $labels = null): void
    {
        $this->getFormatter()->toDetail($this->output, $data, $labels);
    }

    /**
     * Output an error message.
     */
    protected function outputError(string $message): void
    {
        $this->getFormatter()->error($this->output, $message);
    }

    /**
     * Output a success message.
     */
    protected function outputSuccess(string $message): void
    {
        $this->getFormatter()->success($this->output, $message);
    }

    /**
     * Output a warning message.
     */
    protected function outputWarning(string $message): void
    {
        $this->getFormatter()->warning($this->output, $message);
    }

    /**
     * Output list data with automatic format detection.
     *
     * @param  array<int, array<string, mixed>>  $data
     * @param  array<int, string>  $headers
     * @param  array<int, string>  $columnMap  List of data keys in column order
     * @param  array<string, mixed>|null  $meta
     */
    protected function outputList(array $data, array $headers, array $columnMap, ?array $meta = null): void
    {
        if ($this->shouldOutputJson()) {
            $this->outputJson($data, $meta);
        } else {
            $this->outputTable($data, $headers, $columnMap);
            if ($meta !== null && isset($meta['current_page'])) {
                $this->getFormatter()->paginationInfo($this->output, $meta);
            }
        }
    }

    /**
     * Output single resource data with automatic format detection.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, string>|null  $labels
     */
    protected function outputResource(array $data, ?array $labels = null): void
    {
        if ($this->shouldOutputJson()) {
            $this->outputJson($data);
        } else {
            $this->outputDetail($data, $labels);
        }
    }

    /**
     * Output an error with automatic format detection.
     */
    protected function outputFormattedError(string $message, int $statusCode = 0): void
    {
        if ($this->shouldOutputJson()) {
            $this->outputJson(['error' => $message, 'status_code' => $statusCode]);
        } else {
            $this->outputError($message);
        }
    }
}
