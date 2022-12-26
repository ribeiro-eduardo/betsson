FROM php:8.1-alpine

ENV TIMEZONE Europe/Malta
WORKDIR /var/www/html/betsson/

RUN  apk update; \
     apk add bash; \
     apk add curl; \
     curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \	     
     docker-php-ext-install pdo; pdo_mysql; \
     docker-php-ext-install pdo_mysql;  \
     apk add git; 

EXPOSE 8080

