#!/bin/sh

# create password hash from string parameter given

if [ "$#" -ne 1 ];then
	echo
	echo "password.sh"
	echo
	echo "Compute hash value for the string given as parameter."
	echo "Suited to generate passwords for use with PHP password_verify(..)."
	echo
	exit 1
fi
PW=$1
hash=$(php -r "print password_hash('$PW',PASSWORD_DEFAULT);")
echo $hash

