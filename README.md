# Vector Pro

PHP SDK and CLI for the [Vector Pro](https://vectorpro.dev) hosting platform by [BuiltFast](https://builtfast.com).

## Packages

| Package | Description | Install |
|---------|-------------|---------|
| [SDK](./sdk) | PHP library for the Vector Pro API | `composer require built-fast/vector-pro-sdk` |
| [CLI](./cli) | Command-line interface | `composer global require built-fast/vector-pro-cli` |

See individual package READMEs for usage documentation.

## Development

This is a monorepo containing both packages. The CLI depends on the SDK.

```bash
# Install dependencies for both packages
make install

# Run tests
make test          # both packages
make test-sdk      # SDK only
make test-cli      # CLI only

# Code style (Pint)
make fix           # fix both
make fix-sdk
make fix-cli

# Static analysis (PHPStan)
make stan          # both packages
make stan-sdk
make stan-cli

# Full CI check (lint + stan + test)
make check
```

## Requirements

- PHP 8.3+

## License

MIT
