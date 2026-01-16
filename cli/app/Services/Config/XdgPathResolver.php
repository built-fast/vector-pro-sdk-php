<?php

declare(strict_types=1);

namespace App\Services\Config;

/**
 * Resolves configuration paths following XDG Base Directory Specification.
 *
 * Uses XDG_CONFIG_HOME or ~/.config/vector/ on all Unix-like systems (Linux, macOS).
 * Override: VECTOR_CONFIG_DIR environment variable
 */
final class XdgPathResolver
{
    private const APP_NAME = 'vector';

    private const CONFIG_FILE = 'config.json';

    private const CREDENTIALS_FILE = 'credentials.json';

    /**
     * Get the configuration directory path.
     */
    public function getConfigDir(): string
    {
        // Environment variable override takes precedence
        $envDir = getenv('VECTOR_CONFIG_DIR');
        if ($envDir !== false && $envDir !== '') {
            return rtrim($envDir, DIRECTORY_SEPARATOR);
        }

        return $this->getXdgConfigDir();
    }

    /**
     * Get the path to the config file.
     */
    public function getConfigPath(): string
    {
        return $this->getConfigDir().DIRECTORY_SEPARATOR.self::CONFIG_FILE;
    }

    /**
     * Get the path to the credentials file.
     */
    public function getCredentialsPath(): string
    {
        return $this->getConfigDir().DIRECTORY_SEPARATOR.self::CREDENTIALS_FILE;
    }

    /**
     * Ensure the configuration directory exists.
     */
    public function ensureConfigDirExists(): void
    {
        $dir = $this->getConfigDir();
        if (! is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
    }

    /**
     * Get the XDG-compliant configuration directory.
     */
    private function getXdgConfigDir(): string
    {
        $xdgConfigHome = getenv('XDG_CONFIG_HOME');
        if ($xdgConfigHome !== false && $xdgConfigHome !== '') {
            return rtrim($xdgConfigHome, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.self::APP_NAME;
        }

        return $this->getHomeDir().'/.config/'.self::APP_NAME;
    }

    /**
     * Get the user's home directory.
     */
    private function getHomeDir(): string
    {
        $home = getenv('HOME');
        if ($home !== false && $home !== '') {
            return rtrim($home, DIRECTORY_SEPARATOR);
        }

        // Fallback for Windows compatibility (if ever needed)
        $userProfile = getenv('USERPROFILE');
        if ($userProfile !== false && $userProfile !== '') {
            return rtrim($userProfile, DIRECTORY_SEPARATOR);
        }

        // Last resort fallback
        return sys_get_temp_dir();
    }
}
