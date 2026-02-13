#!/usr/bin/env bash
# Recharge les fixtures Doctrine. Utilise Docker si le conteneur php est up, sinon php/symfony en local.
set -e
cd "$(dirname "$0")/.."

if docker compose -f docker-compose.yml ps --status running 2>/dev/null | grep -q cinephoria_php; then
  docker compose -f docker-compose.yml exec php php bin/console doctrine:fixtures:load --no-interaction
elif command -v php >/dev/null 2>&1; then
  php bin/console doctrine:fixtures:load --no-interaction
elif command -v symfony >/dev/null 2>&1; then
  symfony console doctrine:fixtures:load --no-interaction
else
  echo "Impossible de lancer les fixtures: ni Docker (conteneur php), ni php, ni symfony trouvé."
  exit 1
fi
