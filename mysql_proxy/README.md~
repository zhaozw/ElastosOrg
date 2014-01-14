配置MySQL主从同步
=======================
mysql_master：为MySQL主服务器运行在3306端口上。
mysql_slave：为从数据库服务器，运行在3307端口上，作为备份数据库使用。
mysql_slave_readonly：为从数据库服务器，运行在3308端口上，作为读写分离时的只读数据库。

运行时，只需找一个完整可工作的MySQL目录，使用以上三个文件内的my.cnf覆盖原有的my.cnf，并修改相应路径。需要在MySQL目录下分别建立log-bin文件，并设置权限（chown -R mysql:root log-bin/）。详情参见：http://xuwensong.elastos.org/2014/01/07/ubuntu-%E4%B8%8B-mysql-%E4%B8%BB%E4%BB%8E%E5%A4%8D%E5%88%B6%E5%8F%8Amysql-proxy-%E8%AF%BB%E5%86%99%E5%88%86%E7%A6%BB/
