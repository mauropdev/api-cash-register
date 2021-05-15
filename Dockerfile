FROM php:7.4-fpm

LABEL MAINTAINER="Mauricio Perea Rodríguez"

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    apt-transport-https ca-certificates \
    software-properties-common \
    git \
    curl \
    gnupg2 \
    libcurl4-openssl-dev \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libxrender1 \
    libxtst6 \
    libfontconfig

RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype

COPY docker-compose/php/php.ini /usr/local/etc/php/

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pgsql pdo pdo_pgsql json soap xml curl zip mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
