FROM php:7.3-fpm-buster
LABEL maintainer="renaldiyulvianda@yahoo.com"

# Install system dependencies
RUN apt-get update && apt-get install -y \
  nginx \
  git \
  curl \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  nginx \
  zip \
  curl \
  git \
  vim \
  wget \
  # mcrypt preparation
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng-dev \
  # curl
  libcurl4-gnutls-dev \
  # readline
  libedit-dev \
  libreadline-dev \
  # xsl
  libxslt-dev \  
  # zip
  libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
  exif \
  pcntl \
  mysqli \
  pdo_mysql \
  gd \
  opcache \
  readline \
  zip

# mcrypt php ext install
RUN pecl install mcrypt \
  redis

RUN docker-php-ext-enable \
  mcrypt \
  redis 

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

# add config file
COPY ./docker_config/entrypoint.sh /etc/entrypoint.sh
COPY ./docker_config/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker_config/nginx/default.conf /etc/nginx/sites-available/default
# COPY entrypoint.sh /etc/entrypoint.sh
# COPY nginx/default.conf /etc/nginx/conf.d/default.conf
# COPY nginx/default.conf /etc/nginx/sites-available/default

# Get latest Composer and run composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ADD ./ /var/www/html
RUN ls /var/www/html
RUN cd /var/www/html && composer install --no-dev

# chown
RUN chown -R www-data:www-data /var/www/html/storage

# Set working directory
WORKDIR /var/www/html


EXPOSE 80 443

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]