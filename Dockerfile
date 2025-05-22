FROM php:8.4-apache

# Installe les extensions nécessaires à Symfony et MySQL
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Active mod_rewrite pour Apache
RUN a2enmod rewrite

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie les fichiers de config Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copie le code dans le conteneur
COPY . /var/www/html

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

# Travaille dans le répertoire Symfony
WORKDIR /var/www/html

# Installe les dépendances PHP
RUN composer install

# Installe Node.js et Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

# Installe les dépendances JS
RUN yarn install && yarn build
