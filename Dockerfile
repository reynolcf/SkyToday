# Base PHP with Apache
FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Enable PHP error reporting (optional for debugging)
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/docker-php-errors.ini

# Install PHP extensions needed for APIs
RUN docker-php-ext-install curl mbstring

# Copy your website into Apache's default web root
COPY app/html/ /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Apache directory config to allow access
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/site.conf && a2enconf site

# Set working directory
WORKDIR /var/www/html

# Expose port 80
EXPOSE 80