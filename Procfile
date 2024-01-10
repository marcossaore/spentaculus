# web: vendor/bin/heroku-php-apache2 public/
release: chmod -R 775 storage bootstrap/cache && echo APP_KEY= > .env && php artisan key:generate --force && php artisan migrate --force
