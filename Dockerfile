FROM php:8.2-apache

# Enable Apache rewrite (often needed)
RUN a2enmod rewrite

# Copy your site into the web root
COPY app/html/ /var/www/html/

EXPOSE 80