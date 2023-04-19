FROM php:8.2-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev supervisor cron sudo

COPY ./supervisord.conf /etc/supervisor/supervisord.conf

COPY ./crontab /etc/cron.d/crontab

CMD ["/usr/bin/supervisord"]