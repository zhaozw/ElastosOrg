class user
{
		user
		{'mysql':
		  name => 'mysql',
		  ensure => present
		}
		user 
		{'daemon':
		  name => 'daemon',
		  ensure =>present
		}
}
