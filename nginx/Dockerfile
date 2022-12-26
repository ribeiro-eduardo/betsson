FROM ubuntu

RUN apt-get update && apt-get install -y nginx

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/html/

CMD ["nginx"]

EXPOSE 8080