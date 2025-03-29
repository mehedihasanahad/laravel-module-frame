FROM php:8.2-apache
LABEL Author="Prepared by Sabuj and updated by Zaman" Description="A comprehensive docker image to run php:8.2-apache applications like Wordpress, Laravel, etc"
RUN apt-get update -yqq && \
    apt-get install -y apt-utils zip unzip && \
    apt-get install -y nano vim && \
    apt-get install -y libzip-dev libpq-dev && \
    apt-get install -y mariadb-client libmagickwand-dev --no-install-recommends && \
    apt-get install -y redis-server && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev && \
    apt-get install redis-server

RUN set -e; \
    docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype; \
    docker-php-ext-install -j$(nproc) gd && \
    a2enmod rewrite && \
    docker-php-ext-install pdo_mysql zip && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update
RUN apt-get install openssl libssl-dev libcurl4-openssl-dev -y
RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/conf.d/local.ini
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/local.ini
RUN echo "expose_php = off" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "upload_max_filesize = 25M" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "memory_limit = 4096M" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "output_buffering = 4096" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "max_execution_time = 240" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "max_input_time = 150" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "max_input_vars = 5000" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "max_post_size = 40M" >> /usr/local/etc/php/conf.d/local.ini
RUN echo "max_file_uploads = 50M" >> /usr/local/etc/php/conf.d/local.ini


WORKDIR /var/www/html/
COPY . .

RUN chown -R www-data:www-data /var/www/html
COPY default.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80 443

CMD ["apache2ctl", "-D","FOREGROUND"]