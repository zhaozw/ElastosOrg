
SHELL = /bin/sh

#### Start of system configuration section. ####

srcdir = .
topdir = /C/RubyStack-2.3.14-2/ruby/lib/ruby/1.8/i386-mingw32
hdrdir = $(topdir)
VPATH = $(srcdir):$(topdir):$(hdrdir)

DESTDIR = C:
exec_prefix = $(prefix)
prefix = $(DESTDIR)/RubyStack-2.3.14-2/ruby
vendorlibdir = $(vendordir)/$(ruby_version)
dvidir = $(docdir)
vendordir = $(libdir)/ruby/vendor_ruby
docdir = $(datarootdir)/doc/$(PACKAGE)
libdir = $(exec_prefix)/lib
datadir = $(datarootdir)
pdfdir = $(docdir)
libexecdir = $(exec_prefix)/libexec
mandir = $(datarootdir)/man
sbindir = $(exec_prefix)/sbin
infodir = $(datarootdir)/info
localedir = $(datarootdir)/locale
oldincludedir = $(DESTDIR)/usr/include
sitedir = $(libdir)/ruby/site_ruby
bindir = $(exec_prefix)/bin
archdir = $(rubylibdir)/$(arch)
localstatedir = $(prefix)/var
sitearchdir = $(sitelibdir)/$(sitearch)
htmldir = $(docdir)
sitelibdir = $(sitedir)/$(ruby_version)
psdir = $(docdir)
sysconfdir = $(prefix)/etc
sharedstatedir = $(prefix)/com
datarootdir = $(prefix)/share
includedir = $(prefix)/include
rubylibdir = $(libdir)/ruby/$(ruby_version)
vendorarchdir = $(vendorlibdir)/$(sitearch)

CC = gcc
LIBRUBY = lib$(LIBRUBY_SO).a
LIBRUBY_A = lib$(RUBY_SO_NAME)-static.a
LIBRUBYARG_SHARED = -l$(RUBY_SO_NAME)
LIBRUBYARG_STATIC = -l$(RUBY_SO_NAME)-static

RUBY_EXTCONF_H = 
CFLAGS   =  -g -O2 -DFD_SETSIZE=256  $(cflags) -Wall -funroll-loops 
INCFLAGS = -I. -I. -IC:/RubyStack-2.3.14-2/ruby/lib/ruby/1.8/i386-mingw32 -I.
DEFS     = 
CPPFLAGS = -DHAVE_MYSQL_H -DHAVE_ERRMSG_H -DHAVE_MYSQLD_ERROR_H -IC:\RubyStack-2.3.14-2\mysql\include   
CXXFLAGS = $(CFLAGS) 
ldflags  = -L. 
dldflags =  -Wl,--enable-auto-image-base,--enable-auto-import,--export-all
archflag = 
DLDFLAGS = $(ldflags) $(dldflags) $(archflag)
LDSHARED = gcc -shared -s
AR = ar
EXEEXT = .exe

RUBY_INSTALL_NAME = ruby
RUBY_SO_NAME = msvcrt-ruby18
arch = i386-mingw32
sitearch = i386-msvcrt
ruby_version = 1.8
ruby = C:/RubyStack-2.3.14-2/ruby/bin/ruby
RUBY = $(ruby)
RM = rm -f
MAKEDIRS = install -d
INSTALL = /usr/bin/install -c
INSTALL_PROG = $(INSTALL) -m 0755
INSTALL_DATA = $(INSTALL) -m 644
COPY = cp

#### End of system configuration section. ####

preload = 

libpath = . $(libdir) C:\RubyStack-2.3.14-2\mysql\lib\opt
LIBPATH =  -L. -L$(libdir) -LC:\RubyStack-2.3.14-2\mysql\lib\opt
DEFFILE = 

CLEANFILES = mkmf.log
DISTCLEANFILES = 

extout = 
extout_prefix = 
target_prefix = /mysql2
LOCAL_LIBS = 
LIBS = $(LIBRUBYARG_SHARED) -llibmysql  -lshell32 -lws2_32  
SRCS = client.c mysql2_ext.c result.c
OBJS = client.o mysql2_ext.o result.o
TARGET = mysql2
DLLIB = $(TARGET).so
EXTSTATIC = 
STATIC_LIB = 

BINDIR        = $(bindir)
RUBYCOMMONDIR = $(sitedir)$(target_prefix)
RUBYLIBDIR = C:/RubyStack-2.3.14-2/ruby/lib/ruby/gems/1.8/gems/mysql2-0.3.11/lib$(target_prefix)
RUBYARCHDIR = C:/RubyStack-2.3.14-2/ruby/lib/ruby/gems/1.8/gems/mysql2-0.3.11/lib$(target_prefix)

TARGET_SO     = $(DLLIB)
CLEANLIBS     = $(TARGET).so $(TARGET).il? $(TARGET).tds $(TARGET).map
CLEANOBJS     = *.o *.a *.s[ol] *.pdb *.exp *.bak

all:		$(DLLIB)
static:		$(STATIC_LIB)

clean:
		@-$(RM) $(CLEANLIBS) $(CLEANOBJS) $(CLEANFILES)

distclean:	clean
		@-$(RM) Makefile $(RUBY_EXTCONF_H) conftest.* mkmf.log
		@-$(RM) core ruby$(EXEEXT) *~ $(DISTCLEANFILES)

realclean:	distclean
install: install-so install-rb

install-so: $(RUBYARCHDIR)
install-so: $(RUBYARCHDIR)/$(DLLIB)
$(RUBYARCHDIR)/$(DLLIB): $(DLLIB)
	$(INSTALL_PROG) $(DLLIB) $(RUBYARCHDIR)
install-rb: pre-install-rb install-rb-default
install-rb-default: pre-install-rb-default
pre-install-rb: Makefile
pre-install-rb-default: Makefile
$(RUBYARCHDIR):
	$(MAKEDIRS) $@

site-install: site-install-so site-install-rb
site-install-so: install-so
site-install-rb: install-rb

.SUFFIXES: .c .m .cc .cxx .cpp .o

.cc.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.cxx.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.cpp.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.c.o:
	$(CC) $(INCFLAGS) $(CPPFLAGS) $(CFLAGS) -c $<

$(DLLIB): $(OBJS) Makefile
	@-$(RM) $@
	$(LDSHARED) -o $@ $(OBJS) $(LIBPATH) $(DLDFLAGS) $(LOCAL_LIBS) $(LIBS)



$(OBJS): ruby.h defines.h
