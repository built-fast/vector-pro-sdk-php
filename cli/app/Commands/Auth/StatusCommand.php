<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use App\Commands\Concerns\FormatsOutput;
use App\Commands\Concerns\HandlesAuthentication;
use App\Enums\ExitCode;
use App\Services\Config\ConfigRepository;
use LaravelZero\Framework\Commands\Command;

final class StatusCommand extends Command
{
    use FormatsOutput;
    use HandlesAuthentication;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'auth:status
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Show current authentication status';

    /**
     * Execute the console command.
     */
    public function handle(ConfigRepository $config): int
    {
        $hasToken = $this->hasApiKey();
        $source = $this->getTokenSource($config);

        if ($this->shouldOutputJson()) {
            $this->outputJson([
                'authenticated' => $hasToken,
                'source' => $source,
            ]);
        } else {
            if ($hasToken) {
                $this->outputSuccess("Authenticated via {$source}");
            } else {
                $this->output->writeln('Not authenticated.');
                $this->output->writeln('');
                $this->output->writeln('To authenticate, either:');
                $this->output->writeln('  - Run: <info>vector auth login</info>');
                $this->output->writeln('  - Set: <info>VECTOR_API_KEY</info> environment variable');
            }
        }

        return $hasToken ? ExitCode::SUCCESS->code() : ExitCode::AUTH_ERROR->code();
    }

    /**
     * Get the source of the API token.
     */
    private function getTokenSource(ConfigRepository $config): ?string
    {
        if ($this->isUsingEnvApiKey()) {
            return 'environment';
        }

        if ($config->hasApiToken()) {
            return 'config';
        }

        return null;
    }
}
