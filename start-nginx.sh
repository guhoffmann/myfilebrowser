#!/bin/sh

# Start the HTTP file server with nginx.conf file in conf directory!
# The php-fpm module has to be started before!

# Please notice: nginx is started as user www-data normally!
# So the root directory of your docs folder should have
# read/write access for this user!
sudo service php7.3-fpm start
/usr/sbin/nginx -c $(pwd)/conf/nginx-https.conf

