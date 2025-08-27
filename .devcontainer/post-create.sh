#!/usr/bin/env bash
set -e

# Si el proyecto no tiene composer.json, inicializa un nuevo Laravel
if [ ! -f "laravel11-example/composer.json" ]; then
  echo ">> Creando proyecto Laravel 11..."
  composer create-project laravel/laravel:^11.6.1 laravel11-example
fi

# Instala dependencias de PHP y JS
composer install --no-interaction
pnpm install || npm ci

# Copia .env si no existe
if [ ! -f ".env" ]; then
  cp .env.example .env
fi

# Ajusta .env para servicios del compose
# Usamos un case statement para soportar tanto MySQL como PostgreSQL
case "${DB_CONNECTION}" in
  mysql)
    php -r '
    $env = file_get_contents(".env");
    $env = preg_replace("/^DB_CONNECTION=.*/m", "DB_CONNECTION=mysql", $env);
    $env = preg_replace("/^DB_HOST=.*/m", "DB_HOST=db", $env);
    $env = preg_replace("/^DB_PORT=.*/m", "DB_PORT=3306", $env);
    $env = preg_replace("/^DB_DATABASE=.*/m", "DB_DATABASE=laravel", $env);
    $env = preg_replace("/^DB_USERNAME=.*/m", "DB_USERNAME=laravel", $env);
    $env = preg_replace("/^DB_PASSWORD=.*/m", "DB_PASSWORD=admin", $env);
    $env = preg_replace("/^REDIS_HOST=.*/m", "REDIS_HOST=redis", $env);
    $env = preg_replace("/^MAIL_MAILER=.*/m", "MAIL_MAILER=smtp", $env);
    $env = preg_replace("/^MAIL_HOST=.*/m", "MAIL_HOST=mailpit", $env);
    $env = preg_replace("/^MAIL_PORT=.*/m", "MAIL_PORT=1025", $env);
    file_put_contents(".env", $env);
    '
    ;;
  pgsql)
    php -r '
    $env = file_get_contents(".env");
    $env = preg_replace("/^DB_CONNECTION=.*/m", "DB_CONNECTION=pgsql", $env);
    $env = preg_replace("/^DB_HOST=.*/m", "DB_HOST=pgsql", $env);
    $env = preg_replace("/^DB_PORT=.*/m", "DB_PORT=5432", $env);
    $env = preg_replace("/^DB_DATABASE=.*/m", "DB_DATABASE=laravel", $env);
    $env = preg_replace("/^DB_USERNAME=.*/m", "DB_USERNAME=laravel", $env);
    $env = preg_replace("/^DB_PASSWORD=.*/m", "DB_PASSWORD=admin", $env);
    $env = preg_replace("/^REDIS_HOST=.*/m", "REDIS_HOST=redis", $env);
    $env = preg_replace("/^MAIL_MAILER=.*/m", "MAIL_MAILER=smtp", $env);
    $env = preg_replace("/^MAIL_HOST=.*/m", "MAIL_HOST=mailpit", $env);
    $env = preg_replace("/^MAIL_PORT=.*/m", "MAIL_PORT=1025", $env);
    file_put_contents(".env", $env);
    '
    ;;
esac

# Key + migraciones
php artisan key:generate || true

# Corre migraciones si DB ya está arriba
case "${DB_CONNECTION}" in
  mysql)
    php -r 'exit(@mysqli_connect("db","laravel","admin","laravel",3306)?0:1);' \
      && php artisan migrate --force || echo "DB aún no lista, puedes migrar luego."
    ;;
  pgsql)
    # Comando para verificar conexión a PostgreSQL
    php -r 'try { new PDO("pgsql:host=pgsql;dbname=laravel", "laravel", "admin"); exit(0); } catch (PDOException $e) { exit(1); }' \
      && php artisan migrate --force || echo "DB aún no lista, puedes migrar luego."
    ;;
esac

# Herramientas de calidad
composer require --dev laravel/pint phpstan/phpstan nunomaduro/larastan:^2.9

# Scripts NPM/Vite útiles
jq '.scripts += {"dev":"vite","build":"vite build"}' package.json > package.tmp.json && mv package.tmp.json package.json

echo ">> Post-create finalizado."
