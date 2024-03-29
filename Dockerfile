FROM php:7.2-cli

WORKDIR /usr/src/app
COPY ./ ./


# Instalamos git zip y unzip
RUN apt-get update -y && apt-get install git zip unzip -y

# Instalamos composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalamos dependencias
RUN composer install --no-interaction


# corremos servidor de desarrollo
CMD php -S 0.0.0.0:8080 -t public