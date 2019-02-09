#!/bin/sh

# Stop the HTTP file server

PID=$(cat ./myfilebrowser.pid)

if [ $PID ]; then
	echo "Killing process $PID !"
	kill $PID
else
	echo
	echo "MyFileBrowser not active!"
	echo
fi

echo "Please check if php-fpm ist running!"
sudo systemctl stop php-fpm7.0.service

