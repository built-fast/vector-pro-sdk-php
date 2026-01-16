<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Standard exit codes for CLI commands.
 */
enum ExitCode: int
{
    case SUCCESS = 0;
    case GENERAL_ERROR = 1;
    case AUTH_ERROR = 2;
    case VALIDATION_ERROR = 3;
    case NOT_FOUND = 4;
    case NETWORK_ERROR = 5;

    /**
     * Get the exit code value.
     */
    public function code(): int
    {
        return $this->value;
    }
}
