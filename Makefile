.PHONY: app-cs-check app-install app-run app-test assets-build assets-watch help infra-clean infra-rebuild infra-shell-database \
infra-shell-php infra-shell-node infra-show-containers infra-show-images infra-show-logs infra-stop infra-up run

default: help

UID = $(shell id -u)
GID = $(shell id -g)
ID = $(UID):$(GID)
env = dev

#
# Make sure to run the given command in a container identified by the given service.
#
# $(1) the user with which run the command
# $(2) the Docker Compose service
# $(3) the command to run
#
define run-in-container
	@if [ $$(env|grep -c "^CI=") -eq 1 ]; then \
		docker-compose exec --user $(1) -T $(2) /bin/sh -c "$(3)"; \
	elif [ ! -f /.dockerenv ]; then \
		docker-compose exec --user $(1) $(2) /bin/sh -c "$(3)"; \
	else \
		$(3); \
	fi
endef

help:
	@grep -E '^[a-zA-Z_-]+:.*?##.*$$' $(MAKEFILE_LIST) | sort | awk '{split($$0, a, ":"); printf "\033[36m%-30s\033[0m %-30s %s\n", a[1], a[2], a[3]}'

app-cs-check: ## to launch php-cs-fixer in dry mode
	$(call run-in-container,$(ID),php,php -n vendor/bin/php-cs-fixer fix --allow-risky yes --dry-run --diff --verbose)

app-install: ## to install the app
	$(call run-in-container,$(ID),php,composer install)
	$(call run-in-container,$(ID),php,bin/adminconsole sulu:build -n dev)
	$(call run-in-container,$(ID),node,yarn install)

app-test: ## to launch phpunit to test app
	$(call run-in-container,$(ID),php,bin/simple-phpunit --fail-on-warning)

app-install-asset: ## to install asset the bundles project
	$(call run-in-container,$(ID),php,bin/adminconsole assets:install -n dev)

assets-build: ## to install webpack assets
	$(call run-in-container,$(ID),node,yarn run encore ${env})

assets-watch: ## to start webpack in watch mode
	$(call run-in-container,$(ID),node,yarn run encore ${env} --watch)

app-generated-translate: ## to generate translation
	$(call run-in-container,$(ID),php,bin/adminconsole sulu:translate:export)

infra-clean: ## to stop and remove containers, networks, images
	docker-compose down --rmi all

infra-rebuild: ## to clean and up all
	make infra-clean infra-up

infra-shell-database: ## to open a shell session in the database container
	docker-compose exec database /bin/sh

infra-shell-php: ## to open a shell session in the php container
	docker-compose exec php /bin/sh

infra-shell-node: ## to open a shell session in the node container
	docker-compose exec node /bin/sh

infra-show-containers: ## to show all the containers
	docker-compose ps

infra-show-images: ## to show all the images
	docker images -a

infra-show-logs: ## to show logs from containers, specify "c=service_name" to filter logs by container
	docker-compose logs -ft ${c}

infra-stop: ## to stop all the containers
	docker-compose stop

infra-up: ## to create and start all the containers
	if [ ! -f .env ]; then sed "s,{ID},$(ID)," .env.dist > .env; fi
	docker-compose up --build -d
