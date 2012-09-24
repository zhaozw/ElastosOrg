#!/bin/sh
echo $LD_LIBRARY_PATH | egrep "/opt/jenkins/common" > /dev/null
if [ $? -ne 0 ] ; then
PATH="/opt/jenkins/git/bin:/opt/jenkins/perl/bin:/opt/jenkins/java/bin:/opt/jenkins/common/bin:$PATH"
export PATH
LD_LIBRARY_PATH="/opt/jenkins/git/lib:/opt/jenkins/perl/lib:/opt/jenkins/perl/lib/5.8.8/x86_64-linux/CORE:/opt/jenkins/common/lib:$LD_LIBRARY_PATH"
export LD_LIBRARY_PATH
fi

##### GIT ENV #####
PERL5LIB="/opt/jenkins/git/lib/site_perl/5.8.8:$PERL5LIB"			
export PERL5LIB
GITPERLLIB="/opt/jenkins/git/lib/site_perl/5.8.8"
export GITPERLLIB
GIT_EXEC_PATH=/opt/jenkins/git/libexec/git-core/
export GIT_EXEC_PATH
GIT_TEMPLATE_DIR=/opt/jenkins/git/share/git-core/templates
export GIT_TEMPLATE_DIR
GIT_SSL_CAINFO=/opt/jenkins/common/openssl/certs/curl-ca-bundle.crt
export GIT_SSL_CAINFO

##### PERL ENV #####
PERL5LIB="/opt/jenkins/perl/lib/5.8.8:/opt/jenkins/perl/lib/site_perl/5.8.8:/opt/jenkins/perl/lib/5.8.8/x86_64-linux:/opt/jenkins/perl/lib/site_perl/5.8.8/x86_64-linux"
export PERL5LIB
##### JAVA ENV #####
JAVA_HOME=/opt/jenkins/java
export JAVA_HOME


