FROM php:7.2-cli

WORKDIR /usr/src/app
COPY ./ ./

# corremos servidor de desarrollo
CMD php -S 0.0.0.0:8080 -t public