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

