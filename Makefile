.PHONY: start stop init build tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
	./scripts/init.sh

build:
	build/build.sh

tests:
	docker-compose exec php php vendor/bin/simple-phpunit
