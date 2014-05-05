#!/bin/bash
#filename: cut-nginx-log.sh

todaydate=`date --date='today' "+%Y-%m-%d-%H_%M_%S"`
 
mv /opt/rubystack/nginx/logs/access.log /opt/rubystack/nginx/logs/access.log_$todaydate
mv /opt/rubystack/nginx/logs/error.log /opt/rubystack/nginx/logs/error.log_$todaydate

kill -USR1 `cat /opt/rubystack/nginx/logs/nginx.pid`
