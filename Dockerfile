# Use the official PHP image as the base image
FROM php:8.3-fpm

LABEL maintainer="Md. Mustakim Hayder <mustakim@appnap.io>"

# Setup docker arguments.
ARG TIMEZONE="Asia/Dhaka"
ARG WORK_DIR_PATH="/var/www/html"

# Set working directory
WORKDIR ${WORK_DIR_PATH}

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nano \
    sudo 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create php.ini file (replace with your desired content)
COPY ./docker/php.ini /usr/local/etc/php/conf.d

# Copy existing application directory contents
COPY . ${WORK_DIR_PATH}


# Set permissions for /var/www
RUN chown -R www-data:www-data ${WORK_DIR_PATH} && \
    chmod -R 755 ${WORK_DIR_PATH}

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Composer Dump-Autoload
#RUN composer install --no-dev --optimize-autoloader --classmap-authoritative

# Copy the entry point script
# COPY ./docker/docker-entrypoint.sh /usr/local/bin/
# RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ENTRYPOINT [ "/usr/local/bin/docker-entrypoint.sh" ]

CMD ["php-fpm"]