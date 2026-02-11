#!/bin/bash
set -e

# Installer les dépendances si vendor est vide (volume monté)
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --optimize-autoloader
fi

# Builder les assets si public/build n'existe pas (volume monté)
if [ ! -f /var/www/html/public/build/entrypoints.json ]; then
    yarn install && yarn build
fi

# Exécuter les migrations (ignorer si aucune migration)
php bin/console doctrine:migrations:migrate --no-interaction 2>/dev/null || true

# Installer les assets des bundles (EasyAdmin, etc.)
php bin/console assets:install

# Démarrer Apache
exec apache2-foreground
