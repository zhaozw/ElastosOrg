#!/bin/sh
echo $LD_LIBRARY_PATH | egrep "/opt/redmine/common" > /dev/null
if [ $? -ne 0 ] ; then
PATH="/opt/redmine/perl/bin:/opt/redmine/git/bin:/opt/redmine/sqlite/bin:/opt/redmine/ruby/bin:/opt/redmine/subversion/bin:/opt/redmine/php/bin:/opt/redmine/mysql/bin:/opt/redmine/apache2/bin:/opt/redmine/common/bin:$PATH"
export PATH
LD_LIBRARY_PATH="/opt/redmine/ruby/lib/ruby/gems/1.8/gems/passenger-3.0.17/lib:/opt/redmine/perl/lib:/opt/redmine/perl/lib/5.8.8/x86_64-linux/CORE:/opt/redmine/git/lib:/opt/redmine/sqlite/lib:/opt/redmine/ruby/lib:/opt/redmine/subversion/lib:/opt/redmine/mysql/lib:/opt/redmine/apache2/lib:/opt/redmine/common/lib:$LD_LIBRARY_PATH"
export LD_LIBRARY_PATH
fi

##### PERL ENV #####
PERL5LIB="/opt/redmine/perl/lib/5.8.8:/opt/redmine/perl/lib/site_perl/5.8.8:/opt/redmine/perl/lib/5.8.8/x86_64-linux:/opt/redmine/perl/lib/site_perl/5.8.8/x86_64-linux"
export PERL5LIB
##### GIT ENV #####
PERL5LIB="/opt/redmine/git/lib/site_perl/5.8.8:$PERL5LIB"			
export PERL5LIB
GITPERLLIB="/opt/redmine/git/lib/site_perl/5.8.8"
export GITPERLLIB
GIT_EXEC_PATH=/opt/redmine/git/libexec/git-core/
export GIT_EXEC_PATH
GIT_TEMPLATE_DIR=/opt/redmine/git/share/git-core/templates
export GIT_TEMPLATE_DIR
GIT_SSL_CAINFO=/opt/redmine/common/openssl/certs/curl-ca-bundle.crt
export GIT_SSL_CAINFO

##### SQLITE ENV #####
			
##### IMAGEMAGICK ENV #####
MAGICK_HOME="/opt/redmine/common"
export MAGICK_HOME
MAGICK_CONFIGURE_PATH="/opt/redmine/common/lib/ImageMagick-6.7.5/config:/opt/redmine/common/share/ImageMagick-6.7.5/config"
export MAGICK_CONFIGURE_PATH
MAGICK_CODER_MODULE_PATH="/opt/redmine/common/lib/ImageMagick-6.7.5/modules-Q16/coders"
export MAGICK_CODER_MODULE_PATH

LDAPCONF=/opt/redmine/common/etc/openldap/ldap.conf
export LDAPCONF
##### RUBY ENV #####
GEM_HOME="/opt/redmine/ruby/lib/ruby/gems/1.8"
GEM_PATH="/opt/redmine/ruby/lib/ruby/gems/1.8"
RUBY_HOME="/opt/redmine/ruby"
RUBYLIB="/opt/redmine/ruby/lib/ruby/site_ruby/1.8:/opt/redmine/ruby/lib/ruby/site_ruby/1.8/x86_64-linux:/opt/redmine/ruby/lib/ruby/site_ruby/:/opt/redmine/ruby/lib/ruby/vendor_ruby/1.8:/opt/redmine/ruby/lib/ruby/vendor_ruby/1.8/x86_64-linux:/opt/redmine/ruby/lib/ruby/vendor_ruby/:/opt/redmine/ruby/lib/ruby/1.8:/opt/redmine/ruby/lib/ruby/1.8/x86_64-linux:/opt/redmine/ruby/lib/ruby/:/opt/redmine/ruby/lib"
RUBYOPT=rubygems
export GEM_HOME
export GEM_PATH
export RUBY_HOME
export RUBYLIB
export RUBYOPT
##### SUBVERSION ENV #####
			
##### PHP ENV #####
		    
##### MYSQL ENV #####

##### APACHE ENV #####

##### CURL ENV #####
CURL_CA_BUNDLE=/opt/redmine/common/openssl/certs/curl-ca-bundle.crt
export CURL_CA_BUNDLE

