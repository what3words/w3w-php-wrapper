FROM --platform=linux/amd64 php:7.4-apache AS dev-deps
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
RUN apt-get update && apt-get install -y zip
RUN --mount=type=bind,source=./composer.json,target=composer.json \
  --mount=type=bind,source=./composer.lock,target=composer.lock \
  --mount=type=cache,target=/tmp/cache \
  composer install --no-interaction

FROM --platform=linux/amd64 php:7.4-apache AS base
RUN echo 'alias phpunit="/var/www/html/vendor/bin/phpunit"' >> ~/.bashrc

COPY ./src /var/www/html
COPY ./development/src /var/www/html
COPY ./phpunit.xml /var/www/html

FROM base AS development
COPY ./tests /var/www/html/tests
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=dev-deps app/vendor/ /var/www/html/vendor
