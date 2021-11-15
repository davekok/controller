FROM composer AS composer
COPY composer.* /app
RUN composer install --ignore-platform-reqs

FROM php:8.1.0RC6
RUN docker-php-ext-install pcntl
COPY --from=composer /app/vendor /app/vendor
COPY src /app/src
EXPOSE 8080
EXPOSE 8025
WORKDIR /app
CMD ["php", "/app/src/main.php"]
