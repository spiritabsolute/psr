FROM composer AS composer

COPY . /app

RUN composer install \
	--no-interaction \
	--no-plugins \
	--no-scripts \
	--prefer-dist

FROM php:alpine

COPY --from=composer /app /app
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

COPY .docker/php/conf.d/*.ini /usr/local/etc/php/conf.d/

RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug

WORKDIR /app

CMD ["php"]