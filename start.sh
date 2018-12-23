#!/bin/sh

# Start the HTTP file server with lighty and the
# minimal lighttpd.conf file in conf directory!

HOSTNAME=$(uname -n)
SYSTEM=$(uname -o)
DOCS=$(file docs)

if echo "$SYSTEM"|grep "GNU/Linux"
then
	if echo "$HOSTNAME"|grep "raspberrypi"
	then
		# There's Pi working, so start server with link to docs dir.
		# If docs link is not found, give message to
		# make it MANUALLY and exit!
		CONF="conf/$HOSTNAME.conf"
		if echo "$DOCS"|grep "(No such file or directory)"
		then
			echo "No link 'docs' found!"
			echo "Please create link 'docs' in $PWD!"
			exit 1
		fi
		SERVERCMD="/usr/sbin/lighttpd"

	elif echo "$HOSTNAME"|grep "gamepi"
	then
		# There's gamepi working, so start server with link to docs dir.
		# If docs link is not found, give message to
		# make it MANUALLY and exit!
		CONF="conf/$HOSTNAME.conf"
		if echo "$DOCS"|grep "(No such file or directory)"
		then
			echo "No link 'docs' found!"
			echo "Please create link 'docs' in $PWD!"
			exit 1
		fi
		SERVERCMD="sudo /usr/sbin/lighttpd"

	else
		# If there's no Pi working, then start
		# a local server with link to $HOME/Downloads dir.
		CONF="conf/local.conf"
		if echo "$DOCS"|grep "(No such file or directory)"
		then
			echo "No link 'docs' found!"
			echo "Making link 'docs' to $HOME ..."
			ln -s $HOME/Downloads docs
		fi
		SERVERCMD="/usr/sbin/lighttpd"
	fi

elif echo "$SYSTEM"|grep "Android"
then	
	CONF="conf/$SYSTEM.conf"
	if ! echo "$DOCS"|grep "storage"
	then
		ln -s /data/data/com.termux/files/home/storage docs
	fi
	SERVERCMD="lighttpd"
fi

echo "Starting server $HOSTNAME with OS $SYSTEM and config file $CONF ..."

$SERVERCMD -f $(pwd)/$CONF


