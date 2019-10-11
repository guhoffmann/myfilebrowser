#!/bin/sh
# count lines of code written for this project ;)

c=0
arr="*.php cgi-bin/*.php js/router.js js/myFunctions.js css/custom.css *.sh conf/*.*"
for i in $arr;do
	c=$(expr  $c + $(wc -l $i|awk '{print $1}'))
done
echo "Lines of code written: "$c

