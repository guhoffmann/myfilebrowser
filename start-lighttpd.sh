#!/bin/sh

# Start the HTTP file server with lighty and the
# minimal lighttpd.conf file in conf directory!

HOSTNAME=$(uname -n)
SYSTEM=$(uname -o)
DOCS=$(file docs)

if echo "$SYSTEM"|grep "GNU/Linux"; then

	# if "s" parameter given start with https config
	if [ $# -eq 1 ] && [ $1 = "s" ]; then
		CONF="conf/lighttpd-https.conf"
	else
		CONF="conf/lighttpd.conf"
	fi
	if echo "$DOCS"|grep "(No such file or directory)"; then
		echo "No link 'docs' found!"
		echo "Please create link 'docs' in Web-Root!"
		exit 1
	fi
	SERVERCMD="/usr/sbin/lighttpd"

elif echo "$SYSTEM"|grep "Android"; then	

	CONF="conf/Android.conf"
	if ! echo "$DOCS"|grep "storage"
	then
		ln -s /data/data/com.termux/files/home/storage docs
	fi
	SERVERCMD="lighttpd"
fi

echo "Starting server with OS $SYSTEM and config file $CONF ..."

$SERVERCMD -f $(pwd)/$CONF

