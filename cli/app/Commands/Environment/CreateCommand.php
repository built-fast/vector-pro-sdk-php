<?php

declare(strict_types=1);

namespace App\Commands\Environment;

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
    protected $signature = 'env:create
                            {--site= : The site ID (required)}
                            {--name= : Environment name}
                            {--type=staging : Environment type (staging, production)}
                            {--domain= : Environment domain}
                            {--git-branch= : Git branch}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new environment';

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

        $data = $this->buildEnvironmentData();

        if ($data === null) {
            return ExitCode::VALIDATION_ERROR->code();
        }

        try {
            $environment = $client->createEnvironment($siteId, $data);

            if ($this->shouldOutputJson()) {
                $this->outputJson($environment);
            } else {
                $this->outputSuccess("Environment created successfully (ID: {$environment['id']})");
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
     * Build environment data from options.
     *
     * @return array<string, mixed>|null
     */
    private function buildEnvironmentData(): ?array
    {
        /** @var string|null $name */
        $name = $this->option('name');
        /** @var string|null $type */
        $type = $this->option('type');
        /** @var string|null $domain */
        $domain = $this->option('domain');
        /** @var string|null $gitBranch */
        $gitBranch = $this->option('git-branch');

        // Interactive mode if no required options provided
        if ($name === null && $this->isInputTty()) {
            /** @var string|null $name */
            $name = $this->ask('Environment name');
        }

        if ($name === null) {
            $this->outputError('Environment name is required.');

            return null;
        }

        $data = [
            'name' => $name,
            'type' => $type ?? 'staging',
        ];

        if ($domain !== null) {
            $data['domain'] = $domain;
        }

        if ($gitBranch !== null) {
            $data['git_branch'] = $gitBranch;
        }

        return $data;
    }

    /**
     * Check if stdin is a TTY.
     */
    private function isInputTty(): bool
    {
        if (! defined('STDIN')) {
            return true;
        }

        return stream_isatty(STDIN);
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
