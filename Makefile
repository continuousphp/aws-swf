
SHELL := /bin/bash

CID = $(shell docker ps | grep 'aws-swf-php7' | awk '{print $$1}')

connect:
	docker exec -it ${CID} sh

composer-install:
	@docker exec ${CID} /bin/sh -c 'curl -sS https://getcomposer.org/composer.phar -o /usr/bin/composer; chmod +x /usr/bin/composer; /usr/bin/composer install'

phpcs:
	@rm -rf phpcs-reports
	@mkdir phpcs-reports
	@docker exec ${CID} /bin/sh -c 'vendor/bin/phpcs -p --colors --report-full=./phpcs-reports/phpcs-report-full.txt --report-gitblame=./phpcs-reports/phpcs-report-gitblame.txt --report-info=./phpcs-reports/phpcs-report-info.txt --standard=phpcs.xml .'

