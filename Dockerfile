ARG NGINX_VERSION=1.21

# -------------
# generic php base image
# -------------
FROM php:8.1-fpm-alpine AS generic

RUN apk update && \
  apk add --no-cache \
  fcgi git

RUN apk add --no-cache icu-dev openssl acl
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets posix pcntl intl

#RUN docker-php-ext-enable apcu
COPY --from=composer:2.5.1 /usr/bin/composer /usr/local/bin/composer

COPY --from=composer:2.5.1 /usr/bin/composer /usr/local/bin/composer

VOLUME /var/run/php

COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY docker/php/php-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

COPY docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM generic AS base

#ARG CI_JOB_TOKEN=
ARG COMPOSER_AUTH=

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_AUTH=${COMPOSER_AUTH}

WORKDIR /var/www/html

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./

# copy only specifically what we need
COPY src src/

RUN mkdir -p var/cache var/log

VOLUME /var/www/html/var

# ---------
# dev build
# ---------
FROM base AS build_dev

RUN apk add --no-cache bash

COPY tests tests/
COPY phpunit.xml.dist phpstan.neon ./
COPY .env .env.test ./

RUN composer install --prefer-dist --no-scripts --no-progress && \
    composer clear-cache

RUN composer dump-autoload && \
    composer run-script post-install-cmd || \
    chmod +x bin/console && \
    sync

# -------------
# php dev image
# -------------
FROM base AS php_dev

WORKDIR /var/www/html

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug-3.1.2 \
    && apk del .build-deps
RUN docker-php-ext-enable xdebug

ARG COMPOSER_AUTH=

COPY phpunit.xml.dist phpstan.neon ./
COPY --from=build_dev /var/www/html /var/www/html
RUN chown -R www-data var

RUN ln -sf $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Modify memory limit
RUN echo 'memory_limit = -1' >> $PHP_INI_DIR/conf.d/memory_limit_php.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_AUTH=${COMPOSER_AUTH}
