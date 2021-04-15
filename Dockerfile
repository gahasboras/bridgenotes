FROM php:7.4-fpm-alpine

RUN apk add --no-cache nginx supervisor wget zip libzip-dev
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync
RUN install-php-extensions gd xdebug pdo_pgsql zip pdo_mysql

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app
RUN chmod -R 777 /app/storage/
CMD sh /app/docker/startup.sh