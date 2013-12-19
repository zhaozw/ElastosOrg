import 'tests'
import 'autostart'
import 'addusers'

#import 'file.pp'

node 'EOS'
{
#create new users(mysql&daemon)
		include user
}
node 'EOS6'
{
#change file 'autoatart'
		include autostart
}
