#!/usr/bin/env sh
set -eu

echo "[entrypoint] Starting container bootstrap..."

# Wait for PostgreSQL before running migrations.
if [ "${DB_CONNECTION:-pgsql}" = "pgsql" ]; then
	echo "[entrypoint] Waiting for PostgreSQL at ${DB_HOST:-pgsql}:${DB_PORT:-5432}..."
	tries=0
	until php -r '
		$host = getenv("DB_HOST") ?: "pgsql";
		$port = getenv("DB_PORT") ?: "5432";
		$db = getenv("DB_DATABASE") ?: "postgres";
		$user = getenv("DB_USERNAME") ?: "postgres";
		$pass = getenv("DB_PASSWORD") ?: "";
		try {
			new PDO("pgsql:host={$host};port={$port};dbname={$db}", $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
			exit(0);
		} catch (Throwable $e) {
			exit(1);
		}
	'; do
		tries=$((tries + 1))
		if [ "$tries" -ge 30 ]; then
			echo "[entrypoint] Database is not reachable after 30 attempts. Exiting."
			exit 1
		fi
		sleep 2
	done
fi

echo "[entrypoint] Running migrations..."
php artisan migrate --force --no-interaction

if [ ! -L public/storage ]; then
	echo "[entrypoint] Ensuring storage symlink exists..."
	php artisan storage:link || true
fi

echo "[entrypoint] Caching framework artifacts..."
php artisan config:cache
php artisan event:cache || true
php artisan view:cache

# Route caching can fail when closure routes are present.
if php artisan route:cache; then
	echo "[entrypoint] Route cache built."
else
	echo "[entrypoint] Route cache skipped (closure routes detected)."
	php artisan route:clear
fi

echo "[entrypoint] Bootstrap complete. Starting php-fpm."
exec php-fpm
