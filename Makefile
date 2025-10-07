.PHONY: help test coverage phpstan cs cs-fix check install clean

help: ## Display this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Install dependencies
	composer install

test: ## Run tests
	composer test

coverage: ## Generate code coverage report (requires Xdebug/PCOV)
	composer test:coverage

coverage-text: ## Display code coverage in terminal (requires Xdebug/PCOV)
	composer test:coverage-text

phpstan: ## Run PHPStan static analysis
	composer phpstan

cs: ## Check coding standards
	composer cs

cs-fix: ## Fix coding standards violations
	composer cs:fix

check: ## Run all quality checks (tests, phpstan, cs)
	composer check

clean: ## Remove generated files
	rm -rf vendor/ coverage/ .phpunit.cache/ .phpunit.result.cache

.DEFAULT_GOAL := help
