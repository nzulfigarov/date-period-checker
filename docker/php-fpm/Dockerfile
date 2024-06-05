FROM php:8.1-fpm
ENV DEBIAN_FRONTEND noninteractive

# always run apt update when start and after add new source list, then clean up at end.
RUN set -xe; \
    apt-get update -yqq  \
    && pecl channel-update pecl.php.net  \
    && apt-get install -yqq \
        apt-utils \
        gnupg2 \
        git \
        libzip-dev \
        zip \
        unzip \
        curl \
        imagemagick \
        libmagickwand-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && php -m | grep -q 'zip' \
    && apt-get install -yqq \
        libxrender1 \
        libfontconfig1 \
        libx11-dev \
        libjpeg62 \
        libxtst6 \
        fontconfig \
        libjpeg62-turbo \
        xfonts-base \
        xfonts-75dpi \
        wget \
        potrace \
    && pecl install -o -f redis \
    && pecl install imagick \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable imagick


COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./php.ini /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER root

RUN set -xe; php -v | head -n 1 | grep -q "PHP 8.1."

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

ENV LC_ALL POSIX

WORKDIR /var/www

CMD ["php-fpm"]

EXPOSE 9000
