<?php

declare(strict_types=1);

namespace App\Commands\Deployment;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class CreateCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'deploy:create
                            {--site= : The site ID (required)}
                            {--env= : The environment ID (required)}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new deployment (deploy)';

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
        /** @var string|null $envId */
        $envId = $this->option('env');

        if ($siteId === null || $envId === null) {
            $this->outputError('Both --site and --env options are required.');

            return ExitCode::VALIDATION_ERROR->code();
        }

        try {
            $deployment = $client->createDeployment($siteId, $envId);

            if ($this->shouldOutputJson()) {
                $this->outputJson($deployment);
            } else {
                $this->outputSuccess("Deployment initiated (ID: {$deployment['id']})");
                $this->output->writeln('');
                $this->outputResource($deployment, [
                    'id' => 'ID',
                    'status' => 'Status',
                    'commit_sha' => 'Commit SHA',
                    'branch' => 'Branch',
                ]);
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
