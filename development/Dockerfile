FROM --platform=linux/amd64 composer:1.10.0 AS dev-deps
WORKDIR /app
RUN --mount=type=bind,source=./composer.json,target=composer.json \
  --mount=type=bind,source=./composer.lock,target=composer.lock \
  --mount=type=cache,target=/tmp/cache \
  composer install --no-interaction

FROM --platform=linux/amd64 php:5.6-apache AS base
RUN docker-php-ext-install pdo pdo_mysql
RUN echo 'alias phpunit="/var/www/html/vendor/bin/phpunit"' >> ~/.bashrc

COPY ./src /var/www/html
COPY ./development/src /var/www/html
COPY ./phpunit.xml /var/www/html

FROM base AS development
COPY ./tests /var/www/html/tests
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=dev-deps app/vendor/ /var/www/html/vendor