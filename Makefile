.PHONY: help install test coverage cs-check cs-fix stan quality clean

# Default target
help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	composer install

update: ## Update dependencies
	composer update

test: ## Run tests
	composer test

coverage: ## Run tests with coverage report
	composer test:coverage

cs-check: ## Check code style
	composer cs-check

cs-fix: ## Fix code style
	composer cs-fix

stan: ## Run static analysis
	composer stan

quality: ## Run all quality checks
	composer quality

clean: ## Clean up generated files
	rm -rf coverage/
	rm -rf build/
	rm -rf vendor/

ci: ## Run CI checks locally
	make cs-check
	make stan
	make test

help-composer: ## Show available composer scripts
	composer list
