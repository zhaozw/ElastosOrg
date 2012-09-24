		    BitNami Jenkins Stack 1.481-0
		  ============================

1. OVERVIEW

The BitNami Project was created to help spread the adoption of freely
available, high quality open source web applications. BitNami aims to make
it easier than ever to discover, download and install Open Source software 
such as document and content management systems, wikis and blogging 
software.

You can learn more about BitNami at http://bitnami.org

Jenkins, previously known as Hudson, is an open source continuous integration
server. Built with Java, it provides over 400 plugins to support building and 
testing virtually any project. It supports SCM tools including CVS, Subversion, 
Git, Mercurial, Perforce and Clearcase, and can execute Apache Ant and Apache 
Maven based projects as well as arbitrary shell scripts and Windows batch commands. 
It also can monitor executions of remote tasks.

You can learn more about Jenkins at http://jenkins-ci.org

The BitNami Jenkins Stack is an installer that greatly simplifies the
installation of Jenkins and runtime dependencies. It includes ready-to-run
versions of Apache-Tomcat and Java. Jenkins Stack is distributed for 
free under the Apache 2.0 license. Please see the appendix for the specific
licenses of all Open Source components included.

You can learn more about BitNami Stacks at http://bitnami.org/stacks/

2. FEATURES

- Easy to Install

BitNami Stacks are built with one goal in mind: to make it as easy as
possible to install open source software. Our installers completely automate
the process of installing and configuring all of the software included in
each Stack, so you can have everything up and running in just a few clicks.

- Independent

BitNami Stacks are completely self-contained, and therefore do not interfere
with any software already installed on your system. For example, you can
upgrade your system's Java or Apache Tomcat without fear of 'breaking' your
BitNami Stack.

- Integrated

By the time you click the 'finish' button on the installer, the whole stack
will be integrated, configured and ready to go. 

- Relocatable

BitNami Stacks can be installed in any directory. This allows you to have
multiple instances of the same stack, without them interfering with each other. 

3. COMPONENTS

BitNami Jenkins Stack ships with the following software versions:
 
  - Jenkins 1.481
  - Apache Tomcat 7.0.29
  - Git 1.7.11.3
 
On Linux and Windows thea version of the stack includes a bundled JDK 1.6.0_33. 
On OS X, it is required that you have Java 1.5 or later installed in your system. 
It can be downloaded from http://www.apple.com/macosx/features/java/

4. REQUIREMENTS

To install BitNami Jenkins Stack you will need:

    - Intel x86 or compatible processor
    - Minimum of 512 MB RAM
    - Minimum of 400 MB hard drive space
    - TCP/IP protocol support
    - Compatible operanting systems:
      - An x86 or x64 Linux operating system.
      - A 32-bit Windows operating system such as Windows 2000, XP, Vista 
      or Windows Server 2003.
      - An OS X operating system x86 with J2SE 5.0 or later.


5. INSTALLATION

The BitNami Jenkins Stack is distributed as a binary executable installer.
It can be downloaded from:

http://www.bitnami.org/stacks/

The downloaded file will be named something similar to:

bitnami-jenkins-1.481-0-linux-installer.run on Linux or
bitnami-jenkins-1.481-0-linux-x64-installer.run on Linux 64 bit or
bitnami-jenkins-1.481-0-windows-installer.exe on Windows or
bitnami-jenkins-1.481-0-osx-x86-installer.dmg on OS X x86.

On Linux, you will need to give it executable permissions:

chmod 755 bitnami-jenkins-1.481-0-linux-installer.run on Linux


To begin the installation process, invoke from a shell or double-click on
the file you have downloaded, and you will be greeted by the 'Welcome'
screen. You will be asked to choose the installation folder. If the
destination directory does not exist, it will be created as part of the
installation.

The default listening port for Apache Tomcat is 8080 and 8009 for Tomcat shutdown port. If those ports are already in use by other applications, you will be
prompted for alternate ports to use.

The next screen will ask you for the credentials for the administrator user 
that will be created for your jenkins installation. 

If the installer has not been able to find a suitable JDK installation 
in your system, you will have to provide the location of a jdk1.5 or jdk1.6 
installation in the next page.

Once the installation process has been completed, you will see the
'Installation Finished' page. You can choose to launch BitNami
Jenkins Stack at this point. If you do so, your default web browser
will point you to the BitNami local site.

If you receive an error message during installation, please refer to
the Troubleshooting section.

The rest of this guide assumes that you installed BitNami Jenkins
Stack in /home/user/jenkins-1.481-0 on Linux or C:\Program Files\BitNami Jenkins Stack
on Windows or /Applications/jenkins-1.481-0 on OS X and you use port 8080 for Apache 
Tomcat and 3306 for MySQL.

6. STARTING AND STOPPING BITNAMI JENKINS STACK

The BitNami Application Manager is a simple graphical interface included in
the stacks that can start and stop the BitNami servers. It is are located
in the same installation directory.

To start the utility, double click the file named âmanager-linuxâ,
'manager-windows' or 'manager-osx' from your file browser.

Alternatively, you can also start and stop the services manually, as explained below.

To start/stop/restart application on Linux or OS X you can use the included 
ctlscript.sh utility, as shown below:

       ./ctlscript.sh (start|stop|restart)
       ./ctlscript.sh (start|stop|restart) tomcat

  start      - start the service(s)
  stop       - stop  the service(s)
  restart    - restart or start the service(s)

You can start and stop BitNami Jenkins Stack on Windows using the shortcuts
created in the Start Menu, under Programs -> BitNami -> BitNami Service

That will start Apache Tomcat service. Once started, you can open your
browser and access the following URL:

http://127.0.0.1:8080/jenkins/

If you selected an alternate port during installation, for example 18080, the
URL will look like:

http://127.0.0.1:18080/jenkins/


7. DIRECTORY STRUCTURE

The installation process will create several subfolders under the main
installation directory:

	apache-tomcat/: Apache Tomcat Web server.
        git/: Git version control system.
	java/: Java SE Development Kit.
        perl/: Perl (only on Unix)
	apps/jenkins: Applications folder
        apps/jenkins/jenkins_home: Folder in which Jenkins keep archives, configuration files, etc.

(In BitNami Virtual Appliances and AMIs the default installation directory is /opt/bitnami).

8. DEFAULT CONFIGURATION

8.1 Default usernames and passwords

The Jenkins administrative user and password are the ones you set at installation time.
(In BitNami Virtual Appliances and AMIs this default to user/bitnami).

By default Jenkins access control is configured to use Jenkins's own user database and
Project-based Matrix Authorization Strategy. Anonymous user doesn't have access permissions by default and
users sign up is disabled. You can easily change this from the Jenkins interface once you login as the
administrator user created during the installation.

8.2. Setting JENKINS_HOME

The BitNami Jenkins Stack defines the JENKINS_HOME variable is the Tomcat context descriptor, 
in apache-tomcat/config/Catalina/localhost/jenkins.xml 

8.3 Git Plugin

Git plugin is already installed by default. It is configured to use the git binary bundled with the stack which 
is located in your installation directory git/bin/ folder.

9. TROUBLESHOOTING

You can find more information about this product at:

http://wiki.bitnami.org

We also encourage you to post your questions and suggestions at:

http://answers.bitnami.org

We also encourage you to sign up for our newsletter, which we'll use to
announce new releases and new stacks. To do so, just register at:
http://www.bitnami.org/catalog/newsletter.


9.1 Installer

# Installer Payload Error 

If you get the following error while trying to run the installer from the
command line:

"Installer payload initialization failed. This is likely due to an
incomplete or corrupt downloaded file" 

The installer binary is not complete, likely because the file was
not downloaded correctly. You will need to download the file and
repeat the installation process.

# Installer execution error on Linux

If you get the following error while trying to run the installer:

"Cannot open bitnami-jenkins-1.481-0-linux.run: No application suitable for 
automatic installation is available for handling this kind of file."

In some operatings systems you can change permissions with right click ->
properties -> permissions -> execution enable. 

Or from the command line:

$ chmod 755 bitnami-jenkins-1.481-0-linux.run 

9.2 Java 

# Customize application on Linux or on OS X

If you want to reduce application delay, you can change ctlscript.sh
by replacing this line:

export JAVA_OPTS="-XX:MaxPermSize=512m -Xms256m -Xmx512m"

with

export JAVA_OPTS="-XX:MaxPermSize=512m -Xms512m -Xmx1024m"

9.3 Apache Tomcat

If you find any problem starting Apache Tomcat, the first place you
should look at is the error log file that will be created at
jenkins-1.481-0/apache-tomcat/logs/catalina.out. There you will
likely find useful information to determine what the problem is. For issues
not covered in this Quick Start guide, please refer to the BitNami forums
and to the Apache Tomcat documentation, which is located at
http://tomcat.apache.org/tomcat-5.5-doc/index.html.

9.4 Jenkins

For any problem related to Jenkins, please visit
http://wiki.jenkins-ci.org/display/JENKINS/Home

# Automated GUI Testing in Windows

As Tomcat is configured as a service you may find issues when running 
Automated GUI Test (https://wiki.jenkins-ci.org/display/JENKINS/Tomcat). A typical error might look similar to this:

[junit] \# An unexpected error has been detected by HotSpot Virtual Machine:
[junit] \#
[junit] \# EXCEPTION_ACCESS_VIOLATION (0xc0000005) at pc=0x6d07baf4, pid=3260, tid=288
[junit] \#
[junit] \# Java VM: Java HotSpot(TM) Client VM (1.5.0_09-b03 mixed mode, sharing)
[junit] \# Problematic frame:
[junit] \# C [awt.dll+0xbaf4|awt.dll+0xbaf4]
[junit] \#

10. LICENSES

Apache Tomcat is distributed under the Apache License v2.0, which
is located at http://www.apache.org/licenses/LICENSE-2.0

MySQL is distributed under the GNU General Public License v2, which is
located at http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Java and related libraries are distributed under the Common Development 
and Distribution License (CDDL), Version 1.0 and the Sun Microsystems, Inc. 
("Sun") Software License Agreement, wich are located at
http://java.sun.com/j2se/1.5.0/docs/relnotes/license.html

Jenkins is distributed under the Apache License v2.0, which
is located at http://www.apache.org/licenses/LICENSE-2.0

Hibernate is distributed under the GNU General Public License v2, which 
is located at http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 



