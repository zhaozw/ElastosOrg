import 'tests'
import 'autostart'
include 'addusers'

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

node 'EOS'
{
		file
		{
				"/etc/init.d/autostart":
					name => "/etc/init.d/autostart",
					ensure => present,
#					content => "123",
					source => "puppet://EOS2/files/autostart",
#					purge => true,
#					force => true,
#					ignore => ['mysql','tmp'],
#					recurse => true,
					group => root,
					owner => root,
					mode => 600
		}
		include user
}
node 'EOS6'
{
		include autostart
}
