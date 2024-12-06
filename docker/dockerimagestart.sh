#!/bin/sh
echo "dockerimagestart.sh"
echo "env >> /var/www/html/.env"
env >> /var/www/html/.env
echo "cd /var/www/html && php artisan optimize"
cd /var/www/html && php artisan optimize
/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
