# FROM php:8.1.4-fpm-alpine3.14
FROM php:8.2.7-fpm-alpine3.18

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip

# Clear cache
# RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN apk add --no-cache \
    oniguruma-dev
	# supervisor
    # oniguruma-dev pcre-dev autoconf

# Install PHP extensions
# RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-install pdo pdo_mysql mbstring exif

# RUN pecl install -o -f redis \
# &&  rm -rf /tmp/pear \
# &&  docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
# RUN useradd -G www-data,root -u $uid -d /home/$user $user

# ensure www-data user exists
# RUN adduser -D -H -u 1000 -s /bin/bash www-data -G www-data
RUN addgroup -g $uid $user

RUN adduser \
    --disabled-password \
    --home "/home/$user" \
    --ingroup "www-data" \
    --uid "$uid" \
	-G \
	$user \
	$user
	# "$user"

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# COPY ./docker/supervisord.conf /etc/supervisord.conf
# COPY ./docker/app-entrypoint.sh /usr/local/bin/app-entrypoint.sh
# RUN chmod +x /usr/local/bin/app-entrypoint.sh

USER $user

# ENTRYPOINT ["app-entrypoint.sh"]
# CMD ["/usr/bin/supervisord", "-n"]