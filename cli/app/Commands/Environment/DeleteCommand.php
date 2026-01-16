<?php

declare(strict_types=1);

namespace App\Commands\Environment;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class DeleteCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'env:delete
                            {id : The environment ID}
                            {--site= : The site ID (required)}
                            {--force : Skip confirmation}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete an environment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $client = $this->requireAuth();
        if ($client === null) {
            return $this->authErrorCode();
        }

        /** @var string|null $siteId */
        $siteId = $this->option('site');
        if ($siteId === null) {
            $this->outputError('The --site option is required.');

            return ExitCode::VALIDATION_ERROR->code();
        }

        /** @var string $environmentId */
        $environmentId = $this->argument('id');

        // Confirm deletion unless --force is provided
        if (! $this->option('force') && ! $this->shouldOutputJson()) {
            if (! $this->confirm("Are you sure you want to delete environment {$environmentId}? This action cannot be undone.")) {
                $this->outputWarning('Deletion cancelled.');

                return ExitCode::SUCCESS->code();
            }
        }

        try {
            $client->deleteEnvironment($siteId, $environmentId);

            if ($this->shouldOutputJson()) {
                $this->outputJson(['success' => true, 'message' => 'Environment deleted successfully']);
            } else {
                $this->outputSuccess('Environment deleted successfully.');
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
