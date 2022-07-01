FROM alpine:3.15

RUN apk add --no-cache gettext curl nginx php8 php8-fpm php8-opcache php8-pdo php8-pdo_sqlite php8-openssl && \
    mkdir /var/www/html

COPY . /var/www/html

RUN chmod -R 775 /var/www/html && \
    chown -R nginx:nginx /var/www/html

COPY docker/php-fpm.conf /etc/php8/php-fpm.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN mkdir -p /var/run/nginx && \
    mkdir -p /var/run/php-fpm && \
    chown -R nginx:nginx /var/run/ && \
    chmod +x /entrypoint.sh
VOLUME /var/www/html/data
ENTRYPOINT ["/bin/ash", "/entrypoint.sh"]
