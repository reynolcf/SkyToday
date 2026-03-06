FROM php:apache

# 1. Enable Apache mod_rewrite (common for PHP apps)
RUN a2enmod rewrite

# 2. Install any system dependencies if needed
RUN apt-get update && apt-get upgrade -y

# 3. Copy your project files into the image
# This is better than volumes for production/deployment
COPY ./html/ /var/www/html/

# 4. Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html/

# Apache defaults to port 80, which is exactly what cloud hosts expect
EXPOSE 80