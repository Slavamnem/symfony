FROM php:7.4-fpm

MAINTAINER Author="Aleksej Burdash <working.unit.0@gmail.com>"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Set application stage (Prod default)
ARG APP_STAGE=prod
ENV APP_ENV=${APP_STAGE}
ARG APP_DEBUG=0
ENV APP_DEBUG=${APP_DEBUG}

ENV APT_PHP_DEPS \
     libfreetype6-dev \
     libjpeg62-turbo-dev \
     libpng-dev \
     libzip-dev \
     libpq-dev \
     zlib1g-dev \
     libicu-dev \
     libxml2-dev \
     libmagickwand-dev \
     libssl-dev \
     libcurl4-openssl-dev \
     netcat \
     jpegoptim \
     optipng \
     pngquant

ENV PHP_STD_MOD \
    iconv \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    opcache \
    zip \
    exif \
    intl \
    pcntl \
    bcmath \
    sockets \
    soap

ENV PECL_MOD \
    apcu-5.1.12 \
    redis-3.1.6 \
    imagick-3.4.3


#PHP_MOD INSTALL
RUN apt-get update \
    && apt-get install -y $APT_PHP_DEPS --no-install-recommends \
    && docker-php-ext-install -j$(nproc) $PHP_STD_MOD

#PECL_MOD INSTALL (raphf and propo first)
RUN pecl install $PECL_MOD \
    && docker-php-ext-enable redis apcu imagick \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/application/

RUN useradd -d /var/www/application app && chown -hR app:app /var/www/application

COPY --chown=app:app ./ /var/www/application

#ENV CONDITIONAL OPERATIONS
RUN if [ "$APP_ENV" = "dev" ]; then pecl install xdebug-2.9.6 && docker-php-ext-enable xdebug ; else echo "Not in target env. Current ${APP_ENV}"; fi

RUN install -D /var/www/application/docker/${APP_STAGE}.Caddyfile /etc/caddy/Caddyfile
RUN install /var/www/application/docker/${APP_STAGE}.fpm.conf /usr/local/etc/php-fpm.d/docker.conf && install /var/www/application/docker/php-fpm.conf /usr/local/etc/php-fpm.conf && rm -f /usr/local/etc/php-fpm.d/www.conf && rm -f /usr/local/etc/php-fpm.d/zz-docker.conf
RUN install /var/www/application/docker/${APP_STAGE}.99-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

USER app
RUN if [ "$APP_STAGE" = "prod" ] || [ "$APP_STAGE" = "rc" ] || [ "$APP_STAGE" = "stage" ]; then php -d memory_limit=1G bin/console cache:clear ; else echo "Not in target stage. Current ${APP_STAGE}"; fi
RUN if [ "$APP_STAGE" = "prod" ] || [ "$APP_STAGE" = "rc" ] || [ "$APP_STAGE" = "stage" ]; then php bin/console assets:install ; else echo "Not in target stage. Current ${APP_STAGE}"; fi

#VOLUME /var/www/application /etc/caddy
CMD ["php-fpm", "--nodaemonize"]
