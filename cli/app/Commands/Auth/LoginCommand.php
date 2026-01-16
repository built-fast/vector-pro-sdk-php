<?php

declare(strict_types=1);

namespace App\Commands\Auth;

use App\Commands\Concerns\FormatsOutput;
use App\Enums\ExitCode;
use App\Services\Config\ConfigRepository;
use LaravelZero\Framework\Commands\Command;

final class LoginCommand extends Command
{
    use FormatsOutput;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'auth:login
                            {--token= : API token (or use - to read from stdin)}
                            {--json : Output as JSON}
                            {--no-json : Force human-readable output}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Authenticate with the Vector Pro API';

    /**
     * Execute the console command.
     */
    public function handle(ConfigRepository $config): int
    {
        $token = $this->resolveToken();

        if ($token === null) {
            if ($this->shouldOutputJson()) {
                $this->outputJson(['error' => 'No token provided']);
            } else {
                $this->outputError('No token provided. Use --token=<token> or pipe token via stdin.');
            }

            return ExitCode::VALIDATION_ERROR->code();
        }

        $config->setApiToken($token);

        if ($this->shouldOutputJson()) {
            $this->outputJson(['success' => true, 'message' => 'Successfully authenticated']);
        } else {
            $this->outputSuccess('Successfully authenticated. Token stored securely.');
        }

        return ExitCode::SUCCESS->code();
    }

    /**
     * Resolve the API token from various sources.
     */
    private function resolveToken(): ?string
    {
        /** @var string|null $tokenOption */
        $tokenOption = $this->option('token');

        // Token provided via flag
        if ($tokenOption !== null && $tokenOption !== '-') {
            return $tokenOption;
        }

        // Token from stdin (explicit or piped)
        if ($tokenOption === '-' || ! $this->isInputTty()) {
            return $this->readFromStdin();
        }

        // Interactive prompt (TTY mode)
        return $this->promptForToken();
    }

    /**
     * Read token from stdin.
     */
    private function readFromStdin(): ?string
    {
        if (! defined('STDIN')) {
            return null;
        }

        $token = stream_get_contents(STDIN);
        if ($token === false) {
            return null;
        }

        $token = trim($token);

        return $token !== '' ? $token : null;
    }

    /**
     * Prompt for token interactively.
     */
    private function promptForToken(): ?string
    {
        /** @var string|null $token */
        $token = $this->secret('Enter your API token');

        return $token !== null && $token !== '' ? $token : null;
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
}
