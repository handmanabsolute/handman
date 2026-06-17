#!/bin/sh
set -e

echo "=== Laravel Container Startup ==="

# 1. Resolve RAILWAY_PUBLIC_DOMAIN if present in APP_URL
if [ -n "${RAILWAY_PUBLIC_DOMAIN}" ]; then
  APP_URL=$(echo "${APP_URL}" | sed "s/\${RAILWAY_PUBLIC_DOMAIN}/${RAILWAY_PUBLIC_DOMAIN}/g" | sed "s/\$RAILWAY_PUBLIC_DOMAIN/${RAILWAY_PUBLIC_DOMAIN}/g")
  echo "APP_URL resolved to: ${APP_URL}"
fi

# 2. Print environment info (masking passwords)
echo "Diagnosing Environment Variables..."
echo "APP_URL: ${APP_URL}"
echo "DB_CONNECTION: ${DB_CONNECTION}"
echo "DB_HOST: ${DB_HOST}"
echo "DB_PORT: ${DB_PORT}"
echo "DB_DATABASE: ${DB_DATABASE}"
echo "DB_USERNAME: ${DB_USERNAME}"
if [ -n "${DB_PASSWORD}" ]; then
  echo "DB_PASSWORD: [SET (masked)]"
else
  echo "DB_PASSWORD: [NOT SET]"
fi
if [ -n "${DB_URL}" ]; then
  # Mask credentials in DB_URL
  MASKED_DB_URL=$(echo "${DB_URL}" | sed -E 's/\/\/[^:]+:[^@]+@/\/\/*****:*****@/')
  echo "DB_URL: ${MASKED_DB_URL}"
else
  echo "DB_URL: [NOT SET]"
fi

# 1.1 Diagnosing compiled assets
echo "Checking compiled assets in public/build..."
ls -R public/build || echo "public/build directory is missing!"

# 2. Clear config/route/view cache to ensure fresh settings are loaded
echo "Clearing configuration and application cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# 3. Test MySQL connection using PHP
echo "Testing database connection..."
set +e
DB_ERROR=$(php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    exit(0);
} catch (\Exception \$e) {
    echo \$e->getMessage();
    exit(1);
}
" 2>&1)
DB_STATUS=$?
set -e

if [ $DB_STATUS -eq 0 ]; then
  echo "Database connection successful! Running migrations..."
  php artisan migrate --force
else
  echo "WARNING: Database connection failed: ${DB_ERROR}"
  echo "Skipping migrations to prevent container crash."
fi

# 4. Start queue worker in background
echo "Starting Laravel queue worker..."
php artisan queue:work --sleep=3 --tries=3 --timeout=60 &

# 5. Start the server
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
