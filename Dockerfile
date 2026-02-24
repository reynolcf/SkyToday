FROM php:8.2-apache
RUN a2enmod rewrite
COPY app/html/ /var/www/html/
EXPOSE 80