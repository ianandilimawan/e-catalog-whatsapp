#!/bin/bash

echo "🚀 Building frontend assets..."
npm run build

echo "📦 Syncing files to VPS..."
rsync -avz --delete \
    --exclude '.git' \
    --exclude 'node_modules' \
    --exclude 'storage' \
    --exclude '.env' \
    --exclude 'vendor' \
    -e "ssh -i '$HOME/Downloads/ian-macbook.pem' -o StrictHostKeyChecking=no" \
    ./ ianandilimawan@137.59.126.215:/var/www/katalogin/

echo "🔧 Running post-deploy commands on VPS..."
ssh -i "$HOME/Downloads/ian-macbook.pem" -o StrictHostKeyChecking=no ianandilimawan@137.59.126.215 "
    cd /var/www/katalogin
    composer install --no-dev --optimize-autoloader
    php artisan migrate --force
    php artisan optimize:clear
    php artisan optimize
    php artisan view:cache
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
"

echo "✅ Deployment Katalogin Selesai!"
