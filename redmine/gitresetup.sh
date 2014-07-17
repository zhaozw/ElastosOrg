useradd git
useradd daemon
useradd mysql
useradd redis
useradd postgress
touch apache2/logs/access_log
touch apache2/logs/error_log
chown -R daemon:daemon apache2/logs/
chown -R daemon:daemon nginx/var/ngx_pagespeed_cache/
touch postgresql/postgresql.log
chown -R postgres:root postgresql/postgresql.log
chown -R postgres:root postgresql/data/
touch mysql/data/mysqld.log
chown -R mysql:root /mysql/data/
mkdir redis/var/log/
touch redis/var/log/redis-server.log
chown -R redis:root redis/var/log/
chown -R daemon:daemon apps/wordpress/htdocs
chown -R daemon:daemon apps/discuz/htdocs
chown -R daemon:daemon apps/mediawiki/htdocs
chown -R daemon:daemon apps/phpmyadmin/htdocs
chown -R daemon:daemon apps/testlink/htdocs
chown -R daemon:daemon apps/webdav/htdocs
chown -R daemon:daemon apps/wechat/htdocs
chmod -R 777 php/tmp
chmod -R 777 mysql/tmp
