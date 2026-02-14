#!/bin/bash
set -e

# Forcer prod pour éviter de charger DebugBundle (non installé en --no-dev)
export APP_ENV="${APP_ENV:-prod}"

# Symfony exige un fichier .env au démarrage ; en prod (Railway) il est exclu du build.
# Créer un .env vide si absent : les vraies valeurs viennent des variables d'environnement.
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
fi

# DATABASE_URL : utiliser DATABASE_URL si définie, sinon MYSQL_URL (injecté par Railway quand MySQL est connecté)
if [ -n "${DATABASE_URL}" ]; then
    DB_URL="${DATABASE_URL}"
elif [ -n "${MYSQL_URL}" ]; then
    DB_URL="${MYSQL_URL}"
else
    echo "ERREUR: Aucune URL de base. Définir DATABASE_URL ou MYSQL_URL (Variables Cinephoria), ou copier MYSQL_URL depuis le service MySQL." >&2
    exit 1
fi

# Créer .env.local avec les variables pour que Symfony les charge (Apache ne les passe pas à PHP)
cat > /var/www/html/.env.local <<EOF
# Généré au démarrage depuis Railway
APP_ENV=${APP_ENV:-prod}
APP_DEBUG=${APP_DEBUG:-0}
APP_SECRET=${APP_SECRET:-}
DATABASE_URL=${DB_URL}
EOF
chmod 644 /var/www/html/.env.local

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

# Optionnel : charger films + séances de démo (Railway : ajouter variable LOAD_DEMO_DATA=1, déployer, puis retirer la variable)
if [ "${LOAD_DEMO_DATA}" = "1" ]; then
    php bin/console doctrine:fixtures:load --no-interaction --env=prod 2>/dev/null || true
    php bin/console app:showtimes:generate --days=14 --env=prod 2>/dev/null || true
fi

# Créer var/cache et var/log (exclus du build par .dockerignore) et donner les droits à Apache
mkdir -p /var/www/html/var/cache /var/www/html/var/log
chown -R www-data:www-data /var/www/html/var

# Vider le cache prod pour prendre en compte la config à jour (ex. CORS, params)
php bin/console cache:clear --env=prod --no-warmup 2>/dev/null || true
php bin/console cache:warmup --env=prod 2>/dev/null || true
chown -R www-data:www-data /var/www/html/var

# Installer les assets des bundles (EasyAdmin, etc.)
php bin/console assets:install --env=prod

# Démarrer Apache
exec apache2-foreground
