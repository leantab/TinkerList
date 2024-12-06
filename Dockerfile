# Download base image ubuntu 22.04
FROM ubuntu:22.04

# LABEL about the custom image
LABEL maintainer="rob.greer@commandlink.com"
LABEL version="0.1"
LABEL description="This is a custom Docker Image for PHP-FPM and Nginx."

# Disable Prompt During Packages Installation
ARG DEBIAN_FRONTEND=noninteractive

ARG LOCALDEVELOP=false

WORKDIR /var/www/html

# Update Ubuntu Software repository
RUN apt update && apt upgrade -y

# Install CRON
RUN apt install -y cron sudo wget vim

# Install nginx, php-fpm and supervisord from ubuntu repository
RUN apt-get install -y nginx supervisor curl zip unzip imagemagick

# Install PHP 8.2
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
RUN apt update -y
RUN apt install php8.2 -y


RUN apt-get install -y php8.2-fpm php8.2-dev php8.2-curl php8.2-pgsql php8.2-gd php8.2-imagick \
    php8.2-mysql php8.2-bcmath php8.2-xml php8.2-zip php8.2-mbstring \
    php8.2-intl php8.2-readline php8.2-ldap \
    php8.2-msgpack php8.2-igbinary php8.2-redis \
    php8.2-memcached php8.2-pcov php8.2-xdebug php8.2-imagick


# Install SQL Server drivers
RUN apt-get update \
    && apt-get install -y gnupg curl \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/22.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && ACCEPT_EULA=Y apt-get install -y mssql-tools18
# RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc
# RUN source ~/.bashrc
RUN apt-get install -y unixodbc unixodbc-dev

RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv

RUN apt-get install -y openssl
RUN sed -i -E 's/(CipherString\s*=\s*DEFAULT@SECLEVEL=)2/\11/' /etc/ssl/openssl.cnf

# Install composer
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer
RUN curl -sLS https://deb.nodesource.com/setup_18.x | bash -

# Install node and yarn
# RUN apt-get install -y nodejs \
#     && npm install -g npm \
#     && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
#     && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list
#     && apt-get update \
#     && apt-get install -y yarn

# Install mysql and postgres clients
# RUN curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
#     && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list
#     && apt-get update \
#     && apt-get install -y mysql-client \
#     && apt-get install -y postgresql-client-$POSTGRES_VERSION \
#     && apt-get -y autoremove

RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN apt-get clean

# RUN pear config-set ./docker/php_ini /etc/php/8.2/fpm/php.ini

RUN printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.2/mods-available/sqlsrv.ini
RUN printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.2/mods-available/pdo_sqlsrv.ini
RUN phpenmod -v 8.2 sqlsrv pdo_sqlsrv

# Define the ENV variable
ENV nginx_vhost /etc/nginx/sites-available/default
ENV php_conf /etc/php/8.2/fpm/php.ini
ENV nginx_conf /etc/nginx/nginx.conf
ENV supervisor_conf /etc/supervisor/conf.d/supervisord.conf

# copy less restrictive PDF policy for Imagick and GS
COPY ./docker/imagick.policy.xml /etc/ImageMagick-6/policy.xml

# Enable PHP-fpm on nginx virtualhost configuration
COPY ./docker/nginx.default.conf ${nginx_vhost}
RUN sed -i -e 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' ${php_conf} && echo "\ndaemon off;" >> ${nginx_conf}
RUN sed -i -e 's/^upload_max_filesize.*$/upload_max_filesize = 10M/g' ${php_conf}
RUN sed -i -e 's/^memory_limit.*$/memory_limit = 256M/g' ${php_conf}

# Copy supervisor configuration
COPY ./docker/supervisord2.conf ${supervisor_conf}
# Copy composer.lock and composer.json into the working directory
COPY composer.lock composer.json /var/www/html/
# Copy laravel over
COPY . /var/www/html

RUN mkdir -p /run/php
RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /run/php

# Setup xdebug
RUN echo "error_reporting=E_ALL" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "display_startup_errors=On" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "display_errors=On" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.mode=debug" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.discover_client_host=yes" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.idekey=application" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /etc/php/8.2/mods-available/xdebug.ini
RUN echo "xdebug.log_level=0" >> /etc/php/8.2/mods-available/xdebug.ini

RUN cp .env.example .env
RUN composer install
# RUN php artisan migrate --force
# RUN php artisan lighthouse:clear-cache
# RUN php artisan lighthouse:cache
RUN php artisan optimize

# Volume configuration
# VOLUME ["/etc/nginx/sites-enabled", "/etc/nginx/certs", "/etc/nginx/conf.d", "/var/log/nginx", "/var/www/html"]

# Add con script
RUN { echo "* * * * * cd /var/www/html && sudo -u www-data php artisan schedule:run >> /dev/null 2>&1"; } | crontab -

# Enable cron
RUN systemctl enable cron
RUN service cron start

# Copy start.sh script and define default command for the container
COPY ./docker/dockerimagestart.sh /start.sh
CMD ["/start.sh"]

# Expose Port for the Application
EXPOSE 80
