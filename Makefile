.PHONY: help install test coverage quality clean

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

quality: ## Run all quality checks
	composer quality

clean: ## Clean up generated files
	rm -rf coverage/
	rm -rf build/
	rm -rf vendor/

ci: ## Run CI checks locally
	make test

help-composer: ## Show available composer scripts
	composer list
