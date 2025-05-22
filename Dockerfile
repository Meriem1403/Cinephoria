FROM php:8.2-apache

# Installe les extensions nécessaires à Symfony et PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip libonig-dev libpq-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip

RUN a2enmod rewrite

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# (Optionnel) Copie la config Apache custom si besoin
# COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

RUN yarn install && yarn build

EXPOSE 10000

# Pour Apache, change la config du VirtualHost pour Listen 10000,
# OU passe sur php:8.2-cli et CMD serveur Symfony :
# CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
