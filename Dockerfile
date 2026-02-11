FROM php:8.2-apache

# 1. Installer les extensions PHP pour Symfony + MySQL + wkhtmltopdf (PDF)
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip libonig-dev default-libmysqlclient-dev \
    wget ca-certificates fontconfig libfreetype6 libxrender1 libxext6 libx11-6 \
  && docker-php-ext-install intl pdo pdo_mysql zip \
  && arch=$(dpkg --print-architecture) \
  && wget -q "https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-3/wkhtmltox_0.12.6.1-3.bookworm_${arch}.deb" -O /tmp/wkhtmltox.deb \
  && apt-get install -y /tmp/wkhtmltox.deb \
  && rm /tmp/wkhtmltox.deb \
  && ( [ -x /usr/local/bin/wkhtmltopdf ] && ln -sf /usr/local/bin/wkhtmltopdf /usr/bin/wkhtmltopdf ; true )

# 2. Activer rewrite pour Symfony
RUN a2enmod rewrite

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copier tout le projet
COPY . /var/www/html

# 5. Copier la config Apache et l'entrypoint
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# 6. Donner les droits à Apache + rendre bin/console exécutable (pour docker exec)
RUN chown -R www-data:www-data /var/www/html && chmod +x /var/www/html/bin/console

WORKDIR /var/www/html

# 7. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 8. Installer Node.js 18 + Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -  \
  && apt-get install -y nodejs  \
  && npm install -g yarn

# 9. Installer et builder les assets JS/CSS
RUN yarn install && yarn build

# 10. Exposer le port 80 (mappé vers 10000 par docker-compose)
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
