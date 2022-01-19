FROM php:8-cli-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1

ARG USER_ID
ARG GROUP_ID

RUN apk add --update --virtual .build-deps \
        autoconf \
        make \
        gcc \
        g++ \
        libtool \
        curl-dev \
    && apk add --no-cache \
        git \
        wget \
        curl \
        grep \
        bash \
        shadow \
        unzip \
        bash-completion \
        icu-dev \
        postgresql-dev \
        libmemcached-dev \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
    && pecl install \
        xdebug \
        redis \
        memcached \
    && docker-php-ext-enable \
        xdebug \
        redis \
        memcached \
    && docker-php-ext-configure \
        bcmath \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NPROC} gd \
    && docker-php-ext-install \
        opcache \
        pdo_pgsql \
        sockets \
        bcmath \
        intl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/* \
    && rm -rf /var/cache/apk/* \
    && apk del .build-deps \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN echo "alias ll='ls -alF'" >> /home/www-data/.bashrc

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

USER "${USER_ID}:${GROUP_ID}"

WORKDIR /app



