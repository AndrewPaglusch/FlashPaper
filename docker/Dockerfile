FROM php:8.5-fpm-alpine

RUN apk add --no-cache curl nginx && \
    mkdir -p /var/www/html

COPY . /var/www/html

RUN chmod -R 775 /var/www/html && \
    chown -R nginx:nginx /var/www/html

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    rm -rf /usr/local/etc/php-fpm.d

# https://github.com/php/php-src/security/advisories/GHSA-6wmf-4rv5-p3rp
RUN echo 'session.upload_progress.enabled = Off' > "$PHP_INI_DIR/conf.d/upload-progress.ini"

COPY docker/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/entrypoint.sh /entrypoint.sh
COPY docker/settings.php /var/www/html/settings.php

RUN rm -rf /var/www/html/docker && \
    mkdir -p /var/run/nginx /var/run/php-fpm /var/log/php && \
    chown -R nginx:nginx /var/run/ && \
    chmod +x /entrypoint.sh

VOLUME /var/www/html/data

ENTRYPOINT ["/bin/ash", "/entrypoint.sh"]
