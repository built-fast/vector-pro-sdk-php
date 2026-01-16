<?php

declare(strict_types=1);

namespace App\Commands\Deployment;

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
    protected $signature = 'deploy:show
                            {id : The deployment ID}
                            {--site= : The site ID (required)}
                            {--env= : The environment ID (required)}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show details of a deployment';

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

        /** @var string $deploymentId */
        $deploymentId = $this->argument('id');

        try {
            $deployment = $client->getDeployment($siteId, $envId, $deploymentId);

            $this->outputResource($deployment, [
                'id' => 'ID',
                'status' => 'Status',
                'commit_sha' => 'Commit SHA',
                'commit_message' => 'Commit Message',
                'branch' => 'Branch',
                'triggered_by' => 'Triggered By',
                'started_at' => 'Started At',
                'deployed_at' => 'Deployed At',
                'duration' => 'Duration',
                'created_at' => 'Created',
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
