
SHELL = /bin/sh

#### Start of system configuration section. ####

srcdir = .
topdir = /bitnami/rubystack-linux-x64/output/ruby/lib/ruby/1.8/x86_64-linux
hdrdir = $(topdir)
VPATH = $(srcdir):$(topdir):$(hdrdir)
exec_prefix = $(prefix)
prefix = $(DESTDIR)/bitnami/rubystack-linux-x64/output/ruby
sharedstatedir = $(prefix)/com
libdir = $(exec_prefix)/lib
datarootdir = $(prefix)/share
docdir = $(datarootdir)/doc/$(PACKAGE)
vendorarchdir = $(vendorlibdir)/$(sitearch)
mandir = $(datarootdir)/man
sitedir = $(libdir)/ruby/site_ruby
dvidir = $(docdir)
rubylibdir = $(libdir)/ruby/$(ruby_version)
sysconfdir = $(prefix)/etc
archdir = $(rubylibdir)/$(arch)
bindir = $(exec_prefix)/bin
sbindir = $(exec_prefix)/sbin
sitelibdir = $(sitedir)/$(ruby_version)
psdir = $(docdir)
oldincludedir = $(DESTDIR)/usr/include
vendorlibdir = $(vendordir)/$(ruby_version)
datadir = $(datarootdir)
includedir = $(prefix)/include
htmldir = $(docdir)
vendordir = $(libdir)/ruby/vendor_ruby
localedir = $(datarootdir)/locale
libexecdir = $(exec_prefix)/libexec
pdfdir = $(docdir)
infodir = $(datarootdir)/info
localstatedir = $(prefix)/var
sitearchdir = $(sitelibdir)/$(sitearch)

CC = gcc
LIBRUBY = $(LIBRUBY_A)
LIBRUBY_A = lib$(RUBY_SO_NAME)-static.a
LIBRUBYARG_SHARED = -Wl,-R -Wl,$(libdir) -L$(libdir) 
LIBRUBYARG_STATIC = -l$(RUBY_SO_NAME)-static

RUBY_EXTCONF_H = 
CFLAGS   =  -fPIC -I/bitnami/rubystack-linux-x64/output/common/include -m64 -fPIC -I/bitnami/rubystack-linux-x64/output/ImageMagick/include/ImageMagick $(cflags) 
INCFLAGS = -I. -I. -I/bitnami/rubystack-linux-x64/output/ruby/lib/ruby/1.8/x86_64-linux -I.
DEFS     = 
CPPFLAGS = -DHAVE_SQLITE3_INITIALIZE -DHAVE_SQLITE3_NEXT_STMT -DHAVE_SQLITE3_ENABLE_LOAD_EXTENSION -DHAVE_SQLITE3_LOAD_EXTENSION -DAI_ADDRCONFIG=0 -I/bitnami/rubystack-linux-x64/output/common/include -I/bitnami/rubystack-linux-x64/output/sqlite/include -I/bitnami/rubystack-linux-x64/output/common/include/sasl -I/bitnami/rubystack-linux-x64/output/common/include  -DAI_ADDRCONFIG=0 -I/bitnami/rubystack-linux-x64/output/common/include -I/bitnami/rubystack-linux-x64/output/sqlite/include -I/bitnami/rubystack-linux-x64/output/common/include/sasl -I/bitnami/rubystack-linux-x64/output/common/include
CXXFLAGS = $(CFLAGS) 
ldflags  = -L. -L/bitnami/rubystack-linux-x64/output/sqlite/lib -L/bitnami/rubystack-linux-x64/output/common/lib -L/bitnami/rubystack-linux-x64/output/ImageMagick/lib -rdynamic -Wl,-export-dynamic
dldflags = 
archflag = 
DLDFLAGS = $(ldflags) $(dldflags) $(archflag)
LDSHARED = $(CC) -shared
AR = ar
EXEEXT = 

RUBY_INSTALL_NAME = ruby
RUBY_SO_NAME = ruby
arch = x86_64-linux
sitearch = x86_64-linux
ruby_version = 1.8
ruby = /bitnami/rubystack-linux-x64/output/ruby/bin/ruby
RUBY = $(ruby)
RM = rm -f
MAKEDIRS = mkdir -p
INSTALL = /usr/bin/install -c
INSTALL_PROG = $(INSTALL) -m 0755
INSTALL_DATA = $(INSTALL) -m 644
COPY = cp

#### End of system configuration section. ####

preload = 

libpath = . $(libdir) /bitnami/rubystack-linux-x64/output/sqlite/lib
LIBPATH =  -L. -L$(libdir) -Wl,-R$(libdir) -L/bitnami/rubystack-linux-x64/output/sqlite/lib -Wl,-R/bitnami/rubystack-linux-x64/output/sqlite/lib
DEFFILE = 

CLEANFILES = mkmf.log
DISTCLEANFILES = 

extout = 
extout_prefix = 
target_prefix = /sqlite3
LOCAL_LIBS = 
LIBS =  -lsqlite3  -lpthread -lrt -ldl -lcrypt -lm   -lc
SRCS = sqlite3.c database.c backup.c exception.c statement.c
OBJS = sqlite3.o database.o backup.o exception.o statement.o
TARGET = sqlite3_native
DLLIB = $(TARGET).so
EXTSTATIC = 
STATIC_LIB = 

BINDIR        = $(bindir)
RUBYCOMMONDIR = $(sitedir)$(target_prefix)
RUBYLIBDIR = /bitnami/rubystack-linux-x64/output/ruby/lib/ruby/gems/1.8/gems/sqlite3-ruby-1.3.2/lib$(target_prefix)
RUBYARCHDIR = /bitnami/rubystack-linux-x64/output/ruby/lib/ruby/gems/1.8/gems/sqlite3-ruby-1.3.2/lib$(target_prefix)

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

.SUFFIXES: .c .m .cc .cxx .cpp .C .o

.cc.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.cxx.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.cpp.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.C.o:
	$(CXX) $(INCFLAGS) $(CPPFLAGS) $(CXXFLAGS) -c $<

.c.o:
	$(CC) $(INCFLAGS) $(CPPFLAGS) $(CFLAGS) -c $<

$(DLLIB): $(OBJS) Makefile
	@-$(RM) $@
	$(LDSHARED) -o $@ $(OBJS) $(LIBPATH) $(DLDFLAGS) $(LOCAL_LIBS) $(LIBS)



$(OBJS): ruby.h defines.h
