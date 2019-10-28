#!/bin/sh

# Start the HTTP file server with lighty and the
# minimal lighttpd.conf file in conf directory!

HOSTNAME=$(uname -n)
SYSTEM=$(uname -o)
DOCS=$(file docs)

# Start on a normal Linus OS
if echo "$SYSTEM"|grep "GNU/Linux"; then

	# if "-s" parameter given start with https config
	if [ $# -eq 1 ] && [ $1 = "-s" ]; then
		CONF="conf/lighttpd-https.conf"
	elif [ $# -eq 1 ] && [ $1 = "-n" ]; then
		CONF="conf/lighttpd.conf"
	else
		echo
		echo "Syntax: start-lighttpd <Option>"
		echo
		echo "Options: -s   start with lighttpd-https.conf for ssl security"
		echo "         -n   start with lighttpd.conf without extra security"
	exit
	fi
	if echo "$DOCS"|grep "(No such file or directory)"; then
		echo "No link 'docs' found!"
		echo "Please create link 'docs' in Web-Root!"
		exit 1
	fi
	SERVERCMD="/usr/sbin/lighttpd"

# Start on TERMUX
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

