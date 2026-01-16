<?php

declare(strict_types=1);

namespace App\Commands\Site;

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
    protected $signature = 'site:delete
                            {id : The site ID}
                            {--force : Skip confirmation}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete a site';

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

        // Confirm deletion unless --force is provided
        if (! $this->option('force') && ! $this->shouldOutputJson()) {
            if (! $this->confirm("Are you sure you want to delete site {$siteId}? This action cannot be undone.")) {
                $this->outputWarning('Deletion cancelled.');

                return ExitCode::SUCCESS->code();
            }
        }

        try {
            $client->deleteSite($siteId);

            if ($this->shouldOutputJson()) {
                $this->outputJson(['success' => true, 'message' => 'Site deleted successfully']);
            } else {
                $this->outputSuccess('Site deleted successfully.');
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
