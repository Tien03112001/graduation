#!/bin/sh
php /var/www/ee.webcdn/artisan storage:link
php /var/www/ee.webcdn/artisan optimize:clear

php /var/www/ee.webapp/artisan migrate --force
php /var/www/ee.webapp/artisan optimize:clear

php /var/www/ee.bizapp/artisan migrate --force
php /var/www/ee.bizapp/artisan storage:link
php /var/www/ee.bizapp/artisan optimize:clear

php /var/www/ee.chatapp/artisan migrate --force
php /var/www/ee.chatapp/artisan optimize:clear

php /var/www/ee.theme/artisan storage:link
php /var/www/ee.theme/artisan optimize:clear

/usr/bin/supervisord -c /etc/supervisord.conf