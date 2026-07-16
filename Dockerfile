FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libsqlite3-dev libpq-dev unzip curl cron && \
    docker-php-ext-install pdo pdo_sqlite pgsql pdo_pgsql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN cp .env.production .env && php artisan key:generate --force
RUN php artisan migrate --force

# Cron for Laravel scheduler
RUN echo "* * * * * cd /app && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler && crontab /etc/cron.d/laravel-scheduler

EXPOSE 8077
CMD ["sh", "-c", "cron && php artisan serve --host=0.0.0.0 --port=8077"]
