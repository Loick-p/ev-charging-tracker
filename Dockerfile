FROM dunglas/frankenphp:1.13.2-php8-alpine

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache \
    apcu

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/

COPY --from=composer:2.10.3 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .

RUN touch .env

COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN mkdir -p var/cache var/log \
    && composer dump-autoload --classmap-authoritative --no-dev

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
