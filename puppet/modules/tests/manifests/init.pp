filebucket {'main':
	server => "songzi",
	path => "/etc/puppet/buckup"
}
class addgroup
{
	group 
	{"songzig":
	ensure => present,
	gid => 10000,
	name => "songzig",
#	members => "songzi"
	}
}
class adduser
{
	user 
	{"songzi":
	ensure => present,
	shell => "/bin/bash",
	home => "/home/songzi",
	managehome => true,
#password:1234
	password => '$6$q5iipLaR$0gp73T90S0GvLuNF6ejTknerEujxUiYWJssvTt.f5kW9OFW1nKvBXxghmrWT.94ZU5VYsXfzEG33JRlQWgv5U1',
#	groups => "songzig"
           }  
}
class files
{
	file 
	{"/tmp/tmp1.txt":
	name => "/tmp/tmp1.txt",
#	source => "puppet://songzi/files/tmp1.txt",
#	if($operatingsystem==Ubuntu)
	content => "songzi!13",
#	recurse => true,
	backup => main,
	group => root,
	owner => root,
	mode => 600
	}

}
class execute
{
	exec 
	{"test":
	cwd => "/etc",
	command => "touch /tmp/tmp2",
#	creates => "/tmp/tmp2",
	path => "/usr/bin:/usr/sbin:/bin"
	}
}
class cron
{
	cron
	{"heartbeat":
	command => "/etc/init.d/heartbeat start",
	ensure => absent,
	user => root,
	hour => 0,		
	minute => 1,
#	path => "/usr/bin:/usr/sbin:/bin"
	}
}
class service
{
	service
	{"heartbeat":
	ensure => running,
#	require => Package["heartbeat"]
	}
}
