#!/bin/sh

# Start the HTTP file server with nginx nginx-local.conf file in conf directory!
# The php-fpm module has to be started before!

sudo systemctl start php7.0-fpm.service
sudo /usr/sbin/nginx -c $(pwd)/conf/nginx-local.conf

