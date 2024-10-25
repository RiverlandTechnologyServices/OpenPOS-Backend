FROM php:8.3-rc-apache
LABEL authors="OpenPOS"
RUN a2enmod rewrite
RUN a2enmod headers
RUN docker-php-ext-install mysqliRUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
                                     && php composer-setup.php \
                                     && php -r "unlink('composer-setup.php');" \
                                     && mv composer.phar /usr/local/bin/composer \
RUN mkdir /app
WORKDIR /app
COPY [".", "."]
COPY ["./site/*", "/etc/apache2/sites-available/"]