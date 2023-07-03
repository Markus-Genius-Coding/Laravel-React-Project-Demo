FROM php:8.1-apache

USER root

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libwebp-dev \
        libjpeg-dev \
        libzip-dev \
        libonig-dev \
        libfreetype6-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/  --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete \
    && apt-get install git -y \
    && apt-get install nano -y \
    && apt-get install -y cron

RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install opencensus-alpha \
    && docker-php-ext-enable opencensus

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/apache2.conf /etc/apache2/apache2.conf
COPY .docker/php-settings/* /usr/local/etc/php/conf.d/
COPY . /var/www/html/

RUN touch /var/www/html/storage/logs/laravel.log
RUN touch /var/www/html/storage/logs/queues.log

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

RUN composer update
# Copy crontab file to the cron.d directory
COPY .docker/cronjob/crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab
CMD cron

#supervisor
RUN apt-get install -y supervisor
COPY .docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY .docker/supervisor/conf.d /etc/supervisor/conf.d/
CMD ["/usr/bin/supervisord"]

