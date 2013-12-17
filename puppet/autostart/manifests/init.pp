class autostart
{
	file
	{"/etc/init.d/autostart":
		name => "/etc/init.d/autostart",
		ensure => present,
		source => "puppet://EOS2/files/autostart",
		require => Exec["remove","install"],
		group => root,
		owner => root,
		mode => 600
	}
	exec 
	{"remove":
		cwd => "/etc",
		command => "update-rc.d -f autostart remove",
		path => "/usr/bin:/usr/sbin:/bin"
	}
	exec
	{"install":
		cwd => "/etc",
		command => "update-rc.d autostart defaults 95",
		require => Exec["remove"],
		path => "/usr/bin:/usr/sbin:/bin"

	}

}
