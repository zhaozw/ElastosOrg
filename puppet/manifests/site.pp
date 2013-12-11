import 'tests'
#import 'file.pp'
node 'lhg-desktop'
{
#create a group
#       include addgroup
#create a user
#	include adduser
#	include files
#	include execute
#	include cron
	include service
}
