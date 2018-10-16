FROM composer AS composer

COPY ./composer.json /app
COPY ./composer.lock /app

RUN composer install \
	--no-interaction \
	--no-plugins \
	--no-scripts \
	--prefer-dist

FROM php:alpine

COPY . /app
COPY .docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=composer /app/vendor /app/vendor

RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug

WORKDIR /app