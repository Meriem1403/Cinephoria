#!/usr/bin/env bash
# Génère les séances pour les N prochains jours. À appeler quotidiennement (cron).
# Exemple cron (tous les jours à 6h) : 0 6 * * * /chemin/vers/Cinephoria/scripts/generate-showtimes.sh
set -e
cd "$(dirname "$0")/.."
DAYS="${1:-14}"

if docker compose -f docker-compose.yml ps --status running 2>/dev/null | grep -q cinephoria_php; then
  docker compose -f docker-compose.yml exec php php bin/console app:showtimes:generate --days="$DAYS"
elif command -v php >/dev/null 2>&1; then
  php bin/console app:showtimes:generate --days="$DAYS"
else
  echo "Impossible d'exécuter la commande (Docker ou PHP requis)."
  exit 1
fi
