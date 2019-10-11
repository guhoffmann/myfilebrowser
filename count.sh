#!/bin/sh
clear
# count lines of code written for this project ;)
c=0
arr="*.php cgi-bin/*.php"
for i in $arr;do
	echo $i
	c=$(expr  $c + $(wc -l $i|grep "insgesamt\|total"|awk '{print $1}'))
done
echo $c
