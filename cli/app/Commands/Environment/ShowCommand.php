<?php

declare(strict_types=1);

namespace App\Commands\Environment;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class ShowCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'env:show
                            {id : The environment ID}
                            {--site= : The site ID (required)}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show details of an environment';

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

        try {
            $environment = $client->getEnvironment($siteId, $environmentId);

            $this->outputResource($environment, [
                'id' => 'ID',
                'name' => 'Name',
                'type' => 'Type',
                'domain' => 'Domain',
                'status' => 'Status',
                'php_version' => 'PHP Version',
                'git_branch' => 'Git Branch',
                'database_name' => 'Database',
                'created_at' => 'Created',
                'updated_at' => 'Updated',
            ]);

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
