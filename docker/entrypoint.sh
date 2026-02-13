#!/bin/bash
set -e

# Forcer prod pour éviter de charger DebugBundle (non installé en --no-dev)
export APP_ENV="${APP_ENV:-prod}"

# Symfony exige un fichier .env au démarrage ; en prod (Railway) il est exclu du build.
# Créer un .env vide si absent : les vraies valeurs viennent des variables d'environnement.
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# Un seul MPM au démarrage (éviter "More than one MPM loaded" sur Railway)
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

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

# Vider le cache prod pour prendre en compte la config à jour (ex. CORS, params)
php bin/console cache:clear --env=prod --no-warmup 2>/dev/null || true
php bin/console cache:warmup --env=prod 2>/dev/null || true

# Installer les assets des bundles (EasyAdmin, etc.)
php bin/console assets:install --env=prod

# Démarrer Apache
exec apache2-foreground
