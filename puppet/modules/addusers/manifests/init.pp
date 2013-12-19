#create new users(mysql&daemon) used by site.pp with "import 'addusers'"
class user
{
		user
		{'mysql':
		  name => 'mysql',
		  ensure => present,
                  groups => mysql
		}
		user 
		{'daemon':
		  name => 'daemon',
		  ensure =>present,
		  groups => daemon
		}
}
