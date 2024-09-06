FROM php:8.3.0-zts-bookworm AS app

# Установка composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка RoadRunner
COPY --from=ghcr.io/roadrunner-server/roadrunner:2024.2 /usr/bin/rr /usr/local/bin/rr

# Установка скрипта для скачивания пакетов
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Установка пакетов
RUN apt-get update && apt-get install -y \
   git \
   zip \
   unzip
RUN install-php-extensions sockets pdo pdo_pgsql

# Копируем проект
WORKDIR /var/www
COPY . .

# Копируем скрипт для запуска
COPY ./docker/local/php/start.sh /start.sh
RUN chmod +x /start.sh

# Запускаем скрипт start.sh
ENTRYPOINT ["sh"]
CMD ["/start.sh"]

# Открываем порт 8080
EXPOSE 8080
