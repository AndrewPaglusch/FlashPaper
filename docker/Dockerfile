FROM alpine:3.16.3

# To reduce duplication
ENV PHP_VER=php8
# For use in entrypoint.sh
ENV PHPFPM_VER=php-fpm8

RUN apk add --no-cache gettext curl nginx $PHP_VER $PHP_VER-fpm $PHP_VER-opcache $PHP_VER-pdo $PHP_VER-pdo_sqlite $PHP_VER-openssl && \
    mkdir /var/www/html

COPY . /var/www/html

RUN chmod -R 775 /var/www/html && \
    chown -R nginx:nginx /var/www/html

COPY docker/php-fpm.conf /etc/$PHP_VER/php-fpm.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN mkdir -p /var/run/nginx && \
    mkdir -p /var/run/php-fpm && \
    chown -R nginx:nginx /var/run/ && \
    chmod +x /entrypoint.sh
VOLUME /var/www/html/data

HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=10s \
    CMD curl -fSs 127.0.0.1:80/healthz || exit 1

ENTRYPOINT ["/bin/ash", "/entrypoint.sh"]