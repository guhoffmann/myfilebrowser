# myfilebrowser
Simple HTML file browsing application made for ease of use.

- looks and works almost like a file explorer
- supports up-/download of single files and zipped files/folders, multiple delete/copy on server, folder creation, 'clipboard'
- config files for lighttpd, nginx web servers (enabling fastcgi,https etc.)
- can be installed in a TERMUX environment on Smartphones/Tablets for easy data exchange
- bootstrap template for mobile

To run it with your favourite webserver you need to:

- install webserver (tested with lighttpd, nginx)
- install php-cgi, php-fpm, php-sqlite3
- create a 'docs' link to the directory serving your files in myfilebrowser root dir
- start the servers start script (start-lighttpd.sh, start-nginx.sh)

You can adjust the start scripts and the corresponding configuration files
for the server in the conf directory to suit your needs.
