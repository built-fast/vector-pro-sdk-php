# Vector CLI

A command-line interface built with Laravel Zero.

## Requirements

- PHP 8.3+

## Installation

```bash
composer global require priddle/vector-cli
```

## Usage

```bash
vector
```

## Development

### Setup

```bash
git clone https://github.com/priddle/vector-cli.git
cd vector-cli
composer install
```

### Running

```bash
./vector
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
./vendor/bin/phpstan analyze
```

### Building PHAR

```bash
php vector app:build vector
```

## License

MIT
