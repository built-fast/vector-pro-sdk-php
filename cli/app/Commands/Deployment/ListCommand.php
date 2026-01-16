<?php

declare(strict_types=1);

namespace App\Commands\Deployment;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Commands\Concerns\HandlesPagination;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class ListCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;
    use HandlesPagination;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'deploy:list
                            {--site= : The site ID (required)}
                            {--env= : The environment ID (required)}
                            {--page=1 : Page number}
                            {--per-page=15 : Items per page}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all deployments for an environment';

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
            $response = $client->getDeployments($siteId, $envId, $this->getPage(), $this->getPerPage());

            /** @var array<int, array<string, mixed>> $deployments */
            $deployments = $response['deployments'] ?? $response;

            $meta = $this->extractPaginationMeta($response);

            $this->outputList(
                $deployments,
                ['ID', 'Status', 'Commit', 'Deployed At', 'Duration'],
                ['id', 'status', 'commit_sha', 'deployed_at', 'duration'],
                $meta
            );

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
