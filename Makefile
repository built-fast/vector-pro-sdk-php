.PHONY: help install test fix stan check

help:
	@echo "Available commands:"
	@echo "  make install - Install dependencies"
	@echo "  make test    - Run tests"
	@echo "  make fix     - Fix code style (Pint)"
	@echo "  make stan    - Run static analysis (PHPStan)"
	@echo "  make check   - Run full CI suite (Lint, Stan, Test)"

install:
	composer install

test:
	./vendor/bin/pest

fix:
	./vendor/bin/pint

stan:
	./vendor/bin/phpstan analyse

check:
	./vendor/bin/pint --test
	./vendor/bin/phpstan analyse
	./vendor/bin/pest
