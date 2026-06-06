#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

COMPOSE_FILE="${COMPOSE_FILE:-compose.prod.yaml}"
PHP_SERVICE="${PHP_SERVICE:-php}"

run_php_artisan() {
    docker compose -f "$COMPOSE_FILE" exec "$PHP_SERVICE" php artisan "$@"
}

run_npm() {
    if command -v npm >/dev/null 2>&1; then
        npm "$@"
        return
    fi

    docker compose run --rm \
        --user "$(id -u):$(id -g)" \
        -e npm_config_cache=/tmp/.npm \
        node npm "$@"
}

echo "==> Pull latest changes"
git pull --ff-only

echo "==> Ensure production containers are running"
docker compose -f "$COMPOSE_FILE" up -d --build

echo "==> Install PHP dependencies"
docker compose -f "$COMPOSE_FILE" exec "$PHP_SERVICE" composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

echo "==> Install frontend dependencies"
run_npm ci

echo "==> Build frontend assets"
run_npm run build

echo "==> Run database migrations"
run_php_artisan migrate --force

echo "==> Refresh Laravel caches"
run_php_artisan optimize:clear
run_php_artisan config:cache
run_php_artisan route:cache
run_php_artisan view:cache

echo "==> Verify monthly report route and Vite manifest"
docker compose -f "$COMPOSE_FILE" exec "$PHP_SERVICE" php artisan route:list --name=reports
grep -q '"resources/js/monthly-report.jsx"' public/build/manifest.json

echo "Deploy completed."
