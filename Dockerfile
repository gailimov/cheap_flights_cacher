FROM php:7.4.3-alpine3.11

RUN apk add -U \
        postgresql-dev \
    && docker-php-ext-install \
        pdo_pgsql

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:1.9.3 /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

COPY docker/docker-entrypoint.sh /
ENTRYPOINT ["/docker-entrypoint.sh"]

RUN (crontab -l 2>/dev/null; echo "0 0 * * * php /app/app.php prepare") | crontab -
RUN (crontab -l 2>/dev/null; echo "0 */2 * * * php /app/app.php check") | crontab -

CMD [ "/usr/sbin/crond", "-f" ]
