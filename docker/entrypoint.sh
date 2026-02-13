#!/bin/bash
set -e

# Forcer prod pour éviter de charger DebugBundle (non installé en --no-dev)
export APP_ENV="${APP_ENV:-prod}"

# Installer les dépendances si vendor est vide (volume monté)
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-dev --optimize-autoloader
fi

# Builder les assets si public/build n'existe pas (volume monté)
if [ ! -f /var/www/html/public/build/entrypoints.json ]; then
    yarn install && yarn build
fi

# Exécuter les migrations (ignorer si aucune migration)
php bin/console doctrine:migrations:migrate --no-interaction --env=prod 2>/dev/null || true

# Installer les assets des bundles (EasyAdmin, etc.)
php bin/console assets:install --env=prod

# Démarrer Apache
exec apache2-foreground
