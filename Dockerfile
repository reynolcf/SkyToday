FROM php:8.2-apache

# Enable necessary Apache modules
RUN a2enmod rewrite ssl socache_shmcb

# Copy your app files into Apache web root
COPY app/html/ /var/www/html/

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html/
RUN chmod -R 755 /var/www/html/

# Copy SSL certs into container
COPY app/html/mycert.crt /etc/ssl/certs/mycert.crt
COPY app/html/mycert.key /etc/ssl/private/mycert.key

# Replace snakeoil cert in Apache SSL config
RUN sed -i '/SSLCertificateFile.*snakeoil\.pem/c\SSLCertificateFile /etc/ssl/certs/mycert.crt' \
    /etc/apache2/sites-available/default-ssl.conf && \
    sed -i '/SSLCertificateKeyFile.*snakeoil\.key/c\SSLCertificateKeyFile /etc/ssl/private/mycert.key' \
    /etc/apache2/sites-available/default-ssl.conf

# Enable SSL site
RUN a2ensite default-ssl

# Optional system update
RUN apt-get update && apt-get upgrade -y

# Expose ports
EXPOSE 80 443