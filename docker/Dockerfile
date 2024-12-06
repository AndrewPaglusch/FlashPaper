FROM alpine:3.21.0

RUN apk add --no-cache gettext curl nginx php83 php83-fpm php83-opcache php83-pdo php83-pdo_sqlite php83-openssl && \
    mkdir /var/www/html

COPY . /var/www/html

RUN chmod -R 775 /var/www/html && \
    chown -R nginx:nginx /var/www/html

COPY docker/php-fpm.conf /etc/php83/php-fpm.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/settings.php /var/www/html/settings.php

RUN rm -rf /var/www/html/docker && \
    mkdir -p /var/run/nginx && \
    mkdir -p /var/run/php-fpm && \
    chown -R nginx:nginx /var/run/ && \
    chmod +x /entrypoint.sh

VOLUME /var/www/html/data

ENTRYPOINT ["/bin/ash", "/entrypoint.sh"]
