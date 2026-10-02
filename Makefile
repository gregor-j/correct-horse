SHELL := /bin/sh
.DEFAULT_GOAL := help

COMPOSER_IMAGE     := composer:2
COMPOSER_CACHE_DIR := $(HOME)/.cache/composer
DOCKER_USER        := --user "$$(id -u)":"$$(id -g)"
DOCKER_MOUNT       := --volume "$$(pwd)":/app --workdir /app

COMPOSER_UPDATE_FLAGS := --prefer-dist --no-interaction --no-progress
ifeq ($(DEPENDENCIES_LOWEST),1)
COMPOSER_UPDATE_FLAGS += --prefer-lowest
endif

# Optional: set CA_CERT_FILE to a PEM file (e.g. a corporate proxy root CA)
# to make it trusted for HTTPS network access inside the containers used by
# "install" and "audit".
ifdef CA_CERT_FILE
CA_MOUNT     := --volume "$(CA_CERT_FILE)":/tmp/extra-ca.crt:ro
CA_TRUST_CMD := cat /etc/ssl/certs/ca-certificates.crt /tmp/extra-ca.crt > /tmp/ca-bundle.pem && export CURL_CA_BUNDLE=/tmp/ca-bundle.pem SSL_CERT_FILE=/tmp/ca-bundle.pem &&
else
CA_MOUNT     :=
CA_TRUST_CMD :=
endif

.PHONY: help clean check-php-version install test lint beautify sniff audit validate

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*## ' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'

clean: ## Remove vendor/ composer.lock and phpunit cache (reset to a fresh checkout)
	rm -rf vendor composer.lock .phpunit.result.cache

check-php-version:
	@if [ -z "$(PHP_VERSION)" ]; then \
		echo "Error: PHP_VERSION must be set, e.g. 'make $(MAKECMDGOALS) PHP_VERSION=8.1'." >&2; \
		exit 1; \
	fi

install: check-php-version ## Install composer dependencies for PHP_VERSION (set DEPENDENCIES_LOWEST=1 for --prefer-lowest)
	@mkdir -p "$(COMPOSER_CACHE_DIR)"
	docker run --rm -t --init $(DOCKER_USER) \
		--env HTTP_PROXY --env HTTPS_PROXY --env NO_PROXY \
		--env COMPOSER_CACHE_DIR=/tmp/composer-cache \
		--volume "$(COMPOSER_CACHE_DIR)":/tmp/composer-cache \
		$(CA_MOUNT) \
		$(DOCKER_MOUNT) \
		$(COMPOSER_IMAGE) sh -c '\
			status=0; \
			$(CA_TRUST_CMD) \
			composer config platform.php "$(PHP_VERSION)" || status=$$?; \
			if [ $$status -eq 0 ]; then \
				composer update $(COMPOSER_UPDATE_FLAGS) || status=$$?; \
			fi; \
			composer config --unset platform.php >/dev/null 2>&1 || :; \
			composer config --unset platform >/dev/null 2>&1 || :; \
			if php -r '"'"'$$manifest = json_decode(file_get_contents("composer.json")); exit(isset($$manifest->config) && count(get_object_vars($$manifest->config)) === 0 ? 0 : 1);'"'"'; then \
				composer config --unset config >/dev/null 2>&1 || :; \
			fi; \
			rm -f composer.lock; \
			exit $$status \
		'; \
	status=$$?; \
	rm -f composer.lock; \
	exit $$status

test: check-php-version ## Run PHPUnit for PHP_VERSION
	docker run --rm -t --init $(DOCKER_USER) $(DOCKER_MOUNT) \
		"php:$(PHP_VERSION)-cli" php vendor/bin/phpunit

lint: check-php-version ## Syntax-check every .php file in src/ and tests/ for PHP_VERSION
	docker run --rm --init $(DOCKER_USER) $(DOCKER_MOUNT) \
		"php:$(PHP_VERSION)-cli" sh -c "find src tests -type f -name '*.php' -print0 | xargs -0 -n1 php -l"

beautify: check-php-version ## Run PHPCBF (auto-fix code style) for PHP_VERSION
	docker run --rm --init $(DOCKER_USER) $(DOCKER_MOUNT) \
		"php:$(PHP_VERSION)-cli" php vendor/bin/phpcbf

sniff: check-php-version ## Run PHPCS (code style check) for PHP_VERSION
	docker run --rm --init $(DOCKER_USER) $(DOCKER_MOUNT) \
		"php:$(PHP_VERSION)-cli" php vendor/bin/phpcs

audit: ## Run composer audit (checks dependencies for known vulnerabilities)
	docker run --rm -t --init $(DOCKER_USER) \
		--env HTTP_PROXY --env HTTPS_PROXY --env NO_PROXY \
		$(CA_MOUNT) $(DOCKER_MOUNT) \
		$(COMPOSER_IMAGE) sh -c '$(CA_TRUST_CMD) composer audit'

validate: ## Run composer validate --strict
	docker run --rm -t $(DOCKER_USER) $(DOCKER_MOUNT) \
		$(COMPOSER_IMAGE) composer validate --strict
