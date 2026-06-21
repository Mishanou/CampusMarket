#!/bin/sh
set -e

if [ ! -f /var/www/public/build/manifest.json ]; then
    echo "Copying build assets..."
    cp -r /tmp/build/. /var/www/public/build/
fi

exec php-fpm