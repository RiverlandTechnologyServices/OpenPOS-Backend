FROM php:8.3-rc-apache
LABEL authors="OpenPOS"
RUN a2enmod rewrite
RUN a2enmod headers
RUN docker-php-ext-install mysqli
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
                                     && php composer-setup.php \
                                     && php -r "unlink('composer-setup.php');" \
                                     && mv composer.phar /usr/local/bin/composer
RUN mkdir /var/www/openpos
WORKDIR /var/www/openpos
COPY [".", "."]
RUN apt update && apt install -y git
RUN composer install
ENV APACHE_DOCUMENT_ROOT /var/www/openpos/api
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf