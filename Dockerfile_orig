FROM ubuntu:20.04

WORKDIR /var/www/html

RUN apt update && apt upgrade -y

RUN apt install -y sudo wget nano curl gnupg software-properties-common

RUN add-apt-repository ppa:ondrej/php
RUN apt update -y
RUN apt install php8.2 -y

RUN apt install -y php8.2-fpm php8.2-dev php8.2-curl php8.2-gd \
    php8.2-mysql php8.2-bcmatch php8.2-xml \
    php8.2-zip php8.2-mbstring php8.2-readline php8.2-redis \
    php8.2-memcached

RUN apt update -y

RUN phpenmod -v 8.2 mysql pdo_mysql

RUN apt install -y openssl
RUN sed -i -E 's/(CipherString\s*=\s*DEFAULT@SECLEVEL=)2/\11/' /etc/ssl/openssl.cnf

RUN curl -sLS 'https://getcomposer.org/installer' | php -- --install-dir=/usr/bin/ --filename=composer


COPY . /var/www/html/

RUN cp .env.example .env
RUN composer install

RUN php artisan optimize

EXPOSE 80