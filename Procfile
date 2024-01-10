web: vendor/bin/heroku-php-apache2 public/
release: chmod -R 775 storage bootstrap/cache && php artisan migrate --force && php artisan key:generate --force
