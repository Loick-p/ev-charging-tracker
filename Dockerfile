FROM dunglas/frankenphp:latest-php8.3

ARG APP_ENV=prod

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache \
    apcu

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN if [ "$APP_ENV" = "prod" ]; then \
        composer install --no-dev --optimize-autoloader --no-interaction; \
    else \
        composer install --no-interaction; \
    fi

RUN if [ "$APP_ENV" = "prod" ]; then \
        if [ -f "Caddyfile.prod" ]; then \
            cp Caddyfile.prod /etc/caddy/Caddyfile; \
        else \
            cp Caddyfile /etc/caddy/Caddyfile; \
        fi; \
    fi

RUN chown -R www-data:www-data /app && \
    chmod -R 755 /app

RUN if [ "$APP_ENV" = "prod" ]; then \
        php bin/console cache:clear --env=prod --no-debug --no-interaction; \
        php bin/console cache:warmup --env=prod --no-debug --no-interaction; \
    fi

EXPOSE 80 443
