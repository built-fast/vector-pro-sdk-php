<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use App\Commands\Concerns\FormatsOutput;
use App\Enums\ExitCode;
use App\Services\Config\ConfigRepository;
use LaravelZero\Framework\Commands\Command;

final class LogoutCommand extends Command
{
    use FormatsOutput;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'auth:logout
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove stored authentication credentials';

    /**
     * Execute the console command.
     */
    public function handle(ConfigRepository $config): int
    {
        if (! $config->hasApiToken()) {
            if ($this->shouldOutputJson()) {
                $this->outputJson(['success' => true, 'message' => 'Already logged out']);
            } else {
                $this->outputWarning('No stored credentials found. Already logged out.');
            }

            return ExitCode::SUCCESS->code();
        }

        $config->removeApiToken();

        if ($this->shouldOutputJson()) {
            $this->outputJson(['success' => true, 'message' => 'Successfully logged out']);
        } else {
            $this->outputSuccess('Successfully logged out. Credentials removed.');
        }

        return ExitCode::SUCCESS->code();
    }
}
