FROM php:8.4-fpm-alpine

USER root

WORKDIR /home/geocertifica-api

RUN apk add --update --no-cache \
    openssl \
    bash \
    gnupg \
    libcap-dev \
    unzip \
    zlib-dev \
    ldb-dev \
    libwebp-dev \
    postgresql-dev \
    php-pgsql \
    libcap-dev \
    zip \
    libzip-dev \
    freetype \
    libpng \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype-dev \
    libpng-dev \
    libldap \
    openldap-dev \
    chromium \
    freetype \
    libpng \
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    chromium \
    ghostscript

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN pecl install apfd

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY docker/php/php.ini /usr/local/etc/php/app.ini
COPY docker/crontab /etc/crontabs/root
COPY docker/openssl/openssl.cnf /etc/ssl/openssl.cnf

RUN NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)

RUN docker-php-ext-install pdo pgsql pdo_pgsql sockets ldap zip -j$(nproc) gd
RUN docker-php-ext-configure zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /home/geocertifica-api

RUN ln -s public html

ENTRYPOINT ["sh", "./docker/entrypoint.sh"]

EXPOSE 9000
