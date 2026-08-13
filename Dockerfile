FROM php:8.2-apache

# Install PDO MySQL extension for database access
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files into Apache web root
COPY . /var/www/html/

# Create uploads directory and grant write permissions
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

# Expose port 80
EXPOSE 80