<?php

declare(strict_types=1);

namespace App\Commands\Site;

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
    protected $signature = 'site:create
                            {--name= : Site name}
                            {--domain= : Primary domain}
                            {--php-version= : PHP version}
                            {--git-repository= : Git repository URL}
                            {--git-branch=main : Git branch}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new site';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $client = $this->requireAuth();
        if ($client === null) {
            return $this->authErrorCode();
        }

        $data = $this->buildSiteData();

        if ($data === null) {
            return ExitCode::VALIDATION_ERROR->code();
        }

        try {
            $site = $client->createSite($data);

            if ($this->shouldOutputJson()) {
                $this->outputJson($site);
            } else {
                $this->outputSuccess("Site created successfully (ID: {$site['id']})");
                $this->output->writeln('');
                $this->outputResource($site, [
                    'id' => 'ID',
                    'dev_domain' => 'Domain',
                    'status' => 'Status',
                    'dev_php_version' => 'PHP Version',
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
     * Build site data from options or interactive prompts.
     *
     * @return array<string, mixed>|null
     */
    private function buildSiteData(): ?array
    {
        /** @var string|null $name */
        $name = $this->option('name');
        /** @var string|null $domain */
        $domain = $this->option('domain');
        /** @var string|null $phpVersion */
        $phpVersion = $this->option('php-version');
        /** @var string|null $gitRepository */
        $gitRepository = $this->option('git-repository');
        /** @var string|null $gitBranch */
        $gitBranch = $this->option('git-branch');

        // Interactive mode if no required options provided
        if ($name === null && $this->isInputTty()) {
            /** @var string|null $name */
            $name = $this->ask('Site name');
        }

        if ($domain === null && $this->isInputTty()) {
            /** @var string|null $domain */
            $domain = $this->ask('Primary domain');
        }

        if ($name === null || $domain === null) {
            $this->outputError('Name and domain are required.');

            return null;
        }

        $data = [
            'name' => $name,
            'domain' => $domain,
        ];

        if ($phpVersion !== null) {
            $data['php_version'] = $phpVersion;
        }

        if ($gitRepository !== null) {
            $data['git_repository'] = $gitRepository;
            $data['git_branch'] = $gitBranch ?? 'main';
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
