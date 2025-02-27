#!/bin/sh

find . -type f -exec chmod 664 {} \;
find . -type d -exec chmod 775 {} \;

apk add chromium
apk add ghostscript

chown -R www-data:www-data storage/framework

echo ''>/home/geocertifica-api/storage/clockwork/index
echo ''>/home/geocertifica-api/storage/logs/laravel.log

chgrp -R www-data /home/geocertifica-api/storage /home/erp-geocertifica-api/bootstrap/cache
chmod -R ug+rwx /home/geocertifica-api/storage /home/erp-geocertifica-api/bootstrap/cache

chmod -R 775 /home/geocertifica-api/storage
chmod -R 775 /home/geocertifica-api/storage/framework/cache

apk add -U tzdata
cp /usr/share/zoneinfo/America/Campo_Grande /etc/localtime

composer update

php artisan migrate
php artisan optimize:clear
php artisan config:cache

apk add --update supervisor && rm -rf /tmp/* /var/cache/apk/*
cp docker/supervisord/laravel-worker.conf /etc/

composer dump-autoload

crond -f &

#supervisord -c /etc/laravel-worker.conf &

php-fpm
