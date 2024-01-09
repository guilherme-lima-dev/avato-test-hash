FROM php:8.2-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN apk add --update --no-cache --virtual .phpize-deps $PHPIZE_DEPS linux-headers

RUN apk update -q \
    && apk add \
      curl \
      git \
      wget \
      zip  \
      unzip \
      zsh \
      bash \
      vim \
    && install-php-extensions \
      gd \
      redis \
      pdo_sqlite \
      sqlite3 \
      zip \
      sockets \
      pcntl

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Clean repository
RUN rm -rf /var/cache/apk/*

RUN printf '#!/bin/bash\nphp bin/console "$@"' > /usr/bin/sf && \
    chmod +x /usr/bin/sf

WORKDIR /var/www/html

COPY ../deploy.sh ./

RUN ["chmod", "+x", "deploy.sh"]

