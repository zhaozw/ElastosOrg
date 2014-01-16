JAVA_HOME=/opt/tomcatstack-7.0.50-0/java
JRE_HOME=$JAVA_HOME
JAVA_OPTS="-Djava.awt.headless=true -XX:+UseG1GC $JAVA_OPTS "
JAVA_OPTS="-XX:MaxPermSize=256M -Xms256M -Xmx512M $JAVA_OPTS " # java-memory-settings
export JAVA_HOME
export JRE_HOME
export JAVA_OPTS
			    