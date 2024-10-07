FROM php:8.3-rc-apache
LABEL authors="OpenPOS"
RUN a2enmod rewrite
RUN a2enmod headers
RUN docker-php-ext-install mysqli