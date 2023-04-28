FROM arm64v8/alpine:3.17.2

# To reduce duplication
ENV PHP_VER=php81

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

ENTRYPOINT ["/bin/ash", "/entrypoint.sh"]
