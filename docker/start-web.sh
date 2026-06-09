#!/bin/bash
set -e

chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

php /var/www/artisan migrate --force

echo "🎬 Starting Supervisor (Nginx, PHP-FPM, Inertia SSR)..."
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
