FROM php:8.2-apache

# 1. Installer les extensions PHP pour Symfony + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip libonig-dev libpq-dev \
  && docker-php-ext-install intl pdo pdo_pgsql zip

# 2. Activer rewrite pour Symfony
RUN a2enmod rewrite

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copier tout le projet
COPY . /var/www/html

# 5. Copier la config Apache APRÈS la copie du code
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# 6. Donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

# 7. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 8. Installer Node.js 18 + Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -  \
  && apt-get install -y nodejs  \
  && npm install -g yarn

# 9. Installer et builder les assets JS/CSS
RUN yarn install && yarn build

# 10. Exposer le port attendu par Render
EXPOSE 10000
