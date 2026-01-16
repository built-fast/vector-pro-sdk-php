# Vector CLI

A command-line interface for the Vector Pro API, built with Laravel Zero.

## Requirements

- PHP 8.3+

## Installation

```bash
composer global require built-fast/vector-pro-cli
```

Or download the phar from releases.

## Authentication

The CLI needs an API token to authenticate. You can provide it in two ways:

```bash
# Store token in config (recommended)
vector auth:login
vector auth:login --token=your-api-token

# Or use environment variable
export VECTOR_API_KEY=your-api-token
```

Check authentication status:

```bash
vector auth:status
```

Logout (remove stored token):

```bash
vector auth:logout
```

## Usage

### Output Formats

By default, the CLI outputs human-readable tables when run interactively, and JSON when piped:

```bash
vector site:list              # Table output in terminal
vector site:list | jq .       # JSON output when piped
vector site:list --json       # Force JSON output
vector site:list --no-json    # Force table output
```

### Sites

```bash
# List all sites
vector site:list
vector site:list --page=2 --per-page=25

# Show site details
vector site:show <site-id>

# Create a site
vector site:create --domain=example.com --php-version=8.3

# Update a site
vector site:update <site-id> --php-version=8.4

# Delete a site
vector site:delete <site-id>
vector site:delete <site-id> --force  # Skip confirmation

# Suspend/unsuspend
vector site:suspend <site-id>
vector site:unsuspend <site-id>

# Purge CDN cache
vector site:purge-cache <site-id>
vector site:purge-cache <site-id> --path=/specific/path

# Reset passwords
vector site:reset-sftp-password <site-id>
vector site:reset-db-password <site-id>

# View logs
vector site:logs <site-id>
vector site:logs <site-id> --type=access --lines=200
```

### Environments

```bash
# List environments for a site
vector env:list --site=<site-id>

# Show environment details
vector env:show <env-id> --site=<site-id>

# Create environment
vector env:create --site=<site-id> --name=staging --type=staging

# Update environment
vector env:update <env-id> --site=<site-id> --git-branch=develop

# Delete environment
vector env:delete <env-id> --site=<site-id>

# Suspend/unsuspend
vector env:suspend <env-id> --site=<site-id>
vector env:unsuspend <env-id> --site=<site-id>
```

### Deployments

```bash
# List deployments
vector deploy:list --site=<site-id> --env=<env-id>

# Show deployment details
vector deploy:show <deploy-id> --site=<site-id> --env=<env-id>

# Create deployment (deploy)
vector deploy:create --site=<site-id> --env=<env-id>

# Rollback to previous deployment
vector deploy:rollback <deploy-id> --site=<site-id> --env=<env-id>
```

### SSL

```bash
# Check SSL status
vector ssl:status --site=<site-id> --env=<env-id>

# Nudge SSL renewal
vector ssl:nudge --site=<site-id> --env=<env-id>
```

## Configuration

Configuration is stored in `~/.config/vector/`:

- `config.json` - General settings
- `credentials.json` - API token (chmod 0600)

Override config directory with `VECTOR_CONFIG_DIR` environment variable.

## Development

### Setup

```bash
git clone https://github.com/built-fast/vector-cli.git
cd vector-cli/cli
composer install
```

### Running

```bash
php vector
```

### Testing

```bash
./vendor/bin/pest
```

### Code Quality

```bash
# Format code
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyze --level=7
```

### Building PHAR

```bash
php vector app:build vector
```

## License

MIT
