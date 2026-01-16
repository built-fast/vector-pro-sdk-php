<?php

declare(strict_types=1);

namespace App\Commands\Environment;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use LaravelZero\Framework\Commands\Command;
use VectorPro\Sdk\Exceptions\ClientException;

final class UpdateCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'env:update
                            {id : The environment ID}
                            {--site= : The site ID (required)}
                            {--name= : Environment name}
                            {--domain= : Environment domain}
                            {--git-branch= : Git branch}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Update an environment';

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
        $data = $this->buildUpdateData();

        if ($data === []) {
            $this->outputError('No update options provided. Use --name, --domain, or --git-branch.');

            return ExitCode::VALIDATION_ERROR->code();
        }

        try {
            $environment = $client->updateEnvironment($siteId, $environmentId, $data);

            if ($this->shouldOutputJson()) {
                $this->outputJson($environment);
            } else {
                $this->outputSuccess('Environment updated successfully.');
                $this->output->writeln('');
                $this->outputResource($environment, [
                    'id' => 'ID',
                    'name' => 'Name',
                    'type' => 'Type',
                    'domain' => 'Domain',
                    'status' => 'Status',
                ]);
            }

            return ExitCode::SUCCESS->code();
        } catch (ClientException $e) {
            $this->outputFormattedError($e->getMessage(), $e->getStatusCode());

            if ($e->isValidationError()) {
                foreach ($e->getValidationErrors() as $field => $errors) {
                    foreach ($errors as $error) {
                        $this->output->writeln("  <comment>{$field}</comment>: {$error}");
                    }
                }
            }

            return $this->mapStatusToExitCode($e->getStatusCode());
        }
    }

    /**
     * Build update data from options.
     *
     * @return array<string, mixed>
     */
    private function buildUpdateData(): array
    {
        $data = [];

        /** @var string|null $name */
        $name = $this->option('name');
        if ($name !== null) {
            $data['name'] = $name;
        }

        /** @var string|null $domain */
        $domain = $this->option('domain');
        if ($domain !== null) {
            $data['domain'] = $domain;
        }

        /** @var string|null $gitBranch */
        $gitBranch = $this->option('git-branch');
        if ($gitBranch !== null) {
            $data['git_branch'] = $gitBranch;
        }

        return $data;
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
