FROM php:8.1-alpine

ENV TIMEZONE Europe/Malta
WORKDIR /var/www/html/betsson/

RUN  apk update; \
     apk add bash; \
     apk add curl; \
     curl -s https://getcomposer.org/installer | php; \
     alias composer='php composer.phar'; \
     docker-php-ext-install pdo; pdo_mysql; \
     docker-php-ext-install pdo_mysql;  \
     apk add git; 

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/"]


