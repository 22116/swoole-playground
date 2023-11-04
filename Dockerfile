FROM php:8.2-cli-alpine

# Install system dependencies for OpenSwoole and Xdebug
RUN apk update && apk add --no-cache $PHPIZE_DEPS \
    linux-headers \
    libzip-dev \
    zip \
    git \
    inotify-tools

# Install PHP extensions
RUN docker-php-ext-install zip mysqli pdo_mysql

# Install OpenSwoole
RUN pecl install openswoole inotify && \
    docker-php-ext-enable openswoole inotify

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
