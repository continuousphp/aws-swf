SHELL := /bin/bash

GREEN   := "\\033[1;32m"
NORMAL  := "\\033[0;39m"
RED     := "\\033[1;31m"
PINK    := "\\033[1;35m"
BLUE    := "\\033[1;34m"
WHITE   := "\\033[0;02m"
YELLOW  := "\\033[1;33m"
CYAN    := "\\033[1;36m"

CID = $(shell docker ps | grep 'aws-swf-php7' | awk '{print $$1}')

connect:
	docker exec -it ${CID} sh

composer-install:
	@docker exec ${CID} /bin/sh -c 'curl -sS https://getcomposer.org/composer.phar -o /usr/bin/composer; chmod +x /usr/bin/composer; /usr/bin/composer update'

phpcs:
	@rm -rf phpcs-reports
	@mkdir phpcs-reports
	@docker exec ${CID} /bin/sh -c 'vendor/bin/phpcs -p --colors --report-full=./phpcs-reports/phpcs-report-full.txt --report-gitblame=./phpcs-reports/phpcs-report-gitblame.txt --report-info=./phpcs-reports/phpcs-report-info.txt --standard=phpcs.xml .'

aws-config:
	@echo -e $(BLUE)"Enter your AWS Credential information, let blank field you do not use"$(NORMAL); \
	echo -n "AWS Profile :"; \
	read profile; \
	echo -n "AWS Access Key ID :"; \
	read key; \
	echo -n "AWS Secret Access Key :"; \
	read secret; \
	echo -n "AWS region :"; \
	read region; \
	echo "aws.profile=$$profile" > .aws.conf; \
	echo "aws.key=$$key" >> .aws.conf; \
	echo "aws.secret=$$secret" >> .aws.conf; \
	echo "aws.region=$$region" >> .aws.conf; \
	echo -e $(GREEN)"Thanks ! your config is up under .aws.conf"$(NORMAL)

cleanup:
	./vendor/bin/phing -propertyfile .aws.conf cleanup

setup:
	./vendor/bin/phing -propertyfile .aws.conf build
	./vendor/bin/phing -propertyfile .aws.conf init

tu-unit:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests

tu-func:
	./vendor/bin/behat
