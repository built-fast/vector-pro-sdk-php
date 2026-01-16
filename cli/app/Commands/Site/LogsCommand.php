<?php

declare(strict_types=1);

namespace App\Commands\Site;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class LogsCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'site:logs
                            {id : The site ID}
                            {--type=error : Log type (error, access)}
                            {--lines=100 : Number of lines to retrieve}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'View logs for a site';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $client = $this->requireAuth();
        if ($client === null) {
            return $this->authErrorCode();
        }

        /** @var string $siteId */
        $siteId = $this->argument('id');

        $params = [
            'type' => $this->option('type'),
            'lines' => (int) $this->option('lines'),
        ];

        try {
            $result = $client->getSiteLogs($siteId, $params);

            if ($this->shouldOutputJson()) {
                $this->outputJson($result);
            } else {
                $logs = $result['logs'] ?? $result['content'] ?? $result;

                if (is_array($logs)) {
                    foreach ($logs as $line) {
                        $this->output->writeln((string) $line);
                    }
                } elseif (is_string($logs)) {
                    $this->output->writeln($logs);
                } else {
                    $this->outputWarning('No logs found.');
                }
            }

            return ExitCode::SUCCESS->code();
        } catch (ClientException $e) {
            $this->outputFormattedError($e->getMessage(), $e->getStatusCode());

            return $this->mapStatusToExitCode($e->getStatusCode());
        }
    }

    /**
     * Map HTTP status code to exit code.
     */
    private function mapStatusToExitCode(int $statusCode): int
    {
        return match (true) {
            $statusCode === 401, $statusCode === 403 => ExitCode::AUTH_ERROR->code(),
            $statusCode === 404 => ExitCode::NOT_FOUND->code(),
            $statusCode === 422 => ExitCode::VALIDATION_ERROR->code(),
            $statusCode >= 500 => ExitCode::NETWORK_ERROR->code(),
            default => ExitCode::GENERAL_ERROR->code(),
        };
    }
}
