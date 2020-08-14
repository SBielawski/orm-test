FROM php:7.4-cli

# install PDO with PGSQL
RUN apt-get update \
    && apt-get install libpq-dev libzip-dev -y \
    && docker-php-ext-install pdo pdo_pgsql zip

# install composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
