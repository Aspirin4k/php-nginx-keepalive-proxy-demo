FROM php:8.3-fpm
ENV DEBIAN_FRONTEND noninteractive

RUN pecl install redis && pecl install apcu

COPY ./php-fpm.d /usr/local/etc/php-fpm.d
COPY ./php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./php.ini /usr/local/etc/php/php.ini

EXPOSE 9000
