.PHONY: help install test test-cli test-sdk fix fix-cli fix-sdk stan stan-cli stan-sdk check check-cli check-sdk

# Default target
help:
	@echo "Available commands:"
	@echo "  make install  - Install dependencies for CLI and SDK"
	@echo "  make test     - Run tests for CLI and SDK"
	@echo "  make fix      - Fix code style (Pint) for CLI and SDK"
	@echo "  make stan     - Run static analysis (PHPStan) for CLI and SDK"
	@echo "  make check    - Run full CI suite (Lint, Stan, Test) locally"

install:
	@echo "📦 Installing CLI dependencies..."
	cd cli && composer install
	@echo "📦 Installing SDK dependencies..."
	cd sdk && composer install

test: test-cli test-sdk

test-cli:
	@echo "🧪 Testing CLI..."
	cd cli && ./vendor/bin/pest

test-sdk:
	@echo "🧪 Testing SDK..."
	cd sdk && ./vendor/bin/pest

fix: fix-cli fix-sdk

fix-cli:
	@echo "🎨 Fixing CLI code style..."
	cd cli && ./vendor/bin/pint

fix-sdk:
	@echo "🎨 Fixing SDK code style..."
	cd sdk && ./vendor/bin/pint

stan: stan-cli stan-sdk

stan-cli:
	@echo "🔍 Analyzing CLI..."
	cd cli && ./vendor/bin/phpstan analyse

stan-sdk:
	@echo "🔍 Analyzing SDK..."
	cd sdk && ./vendor/bin/phpstan analyse

check: check-cli check-sdk

check-cli:
	@echo "Checking CLI..."
	cd cli && ./vendor/bin/pint --test
	cd cli && ./vendor/bin/phpstan analyse
	cd cli && ./vendor/bin/pest

check-sdk:
	@echo "Checking SDK..."
	cd sdk && ./vendor/bin/pint --test
	cd sdk && ./vendor/bin/phpstan analyse
	cd sdk && ./vendor/bin/pest
