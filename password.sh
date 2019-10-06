#!/bin/sh

# create password hash

PW=$1
hash=$(php -r "print password_hash('$PW',PASSWORD_DEFAULT);")
echo $hash

