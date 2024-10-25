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
COPY ["./site/*", "/etc/apache2/sites-available/"]