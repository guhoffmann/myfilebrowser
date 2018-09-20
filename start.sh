#!/bin/sh

# Start the HTTP file server with lighty and the
# minimal lighttpd.conf file in conf directory!

HOSTNAME=$(uname -n)
SYSTEM=$(uname -o)
PAR=$1

if echo "$SYSTEM"|grep "GNU/Linux"
then
	if echo "$HOSTNAME"|grep "raspberrypi"
	then
		CONF="conf/$HOSTNAME.conf"
	else
		CONF="conf/local.conf"
	fi
elif echo "$SYSTEM"|grep "Android"
then	
	CONF="conf/$SYSTEM.conf"
fi

echo "Starting server $HOSTNAME with OS $SYSTEM with config file $CONF ..."
#cat $(pwd)/$CONF
lighttpd -f $(pwd)/$CONF

