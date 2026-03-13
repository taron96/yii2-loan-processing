FROM php:8.2-fpm

RUN sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources || true \
    && apt-get clean \
    && apt-get update --fix-missing \
    && apt-get install -y libpq-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql
