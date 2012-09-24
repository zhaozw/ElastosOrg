# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{rack}
  s.version = "1.1.3"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Christian Neukirchen"]
  s.date = %q{2011-12-28}
  s.default_executable = %q{rackup}
  s.description = %q{Rack provides minimal, modular and adaptable interface for developing
web applications in Ruby.  By wrapping HTTP requests and responses in
the simplest way possible, it unifies and distills the API for web
servers, web frameworks, and software in between (the so-called
middleware) into a single method call.

Also see http://rack.rubyforge.org.
}
  s.email = %q{chneukirchen@gmail.com}
  s.executables = ["rackup"]
  s.files = ["test/spec_rack_auth_basic.rb", "test/spec_rack_auth_digest.rb", "test/spec_rack_builder.rb", "test/spec_rack_camping.rb", "test/spec_rack_cascade.rb", "test/spec_rack_cgi.rb", "test/spec_rack_chunked.rb", "test/spec_rack_commonlogger.rb", "test/spec_rack_conditionalget.rb", "test/spec_rack_config.rb", "test/spec_rack_content_length.rb", "test/spec_rack_content_type.rb", "test/spec_rack_deflater.rb", "test/spec_rack_directory.rb", "test/spec_rack_etag.rb", "test/spec_rack_fastcgi.rb", "test/spec_rack_file.rb", "test/spec_rack_handler.rb", "test/spec_rack_head.rb", "test/spec_rack_lint.rb", "test/spec_rack_lobster.rb", "test/spec_rack_lock.rb", "test/spec_rack_logger.rb", "test/spec_rack_methodoverride.rb", "test/spec_rack_mock.rb", "test/spec_rack_mongrel.rb", "test/spec_rack_nulllogger.rb", "test/spec_rack_recursive.rb", "test/spec_rack_request.rb", "test/spec_rack_response.rb", "test/spec_rack_rewindable_input.rb", "test/spec_rack_runtime.rb", "test/spec_rack_sendfile.rb", "test/spec_rack_session_cookie.rb", "test/spec_rack_session_memcache.rb", "test/spec_rack_session_pool.rb", "test/spec_rack_showexceptions.rb", "test/spec_rack_showstatus.rb", "test/spec_rack_static.rb", "test/spec_rack_thin.rb", "test/spec_rack_urlmap.rb", "test/spec_rack_utils.rb", "test/spec_rack_webrick.rb", "test/spec_rackup.rb", "bin/rackup"]
  s.homepage = %q{http://rack.rubyforge.org}
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{rack}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{a modular Ruby webserver interface}
  s.test_files = ["test/spec_rack_auth_basic.rb", "test/spec_rack_auth_digest.rb", "test/spec_rack_builder.rb", "test/spec_rack_camping.rb", "test/spec_rack_cascade.rb", "test/spec_rack_cgi.rb", "test/spec_rack_chunked.rb", "test/spec_rack_commonlogger.rb", "test/spec_rack_conditionalget.rb", "test/spec_rack_config.rb", "test/spec_rack_content_length.rb", "test/spec_rack_content_type.rb", "test/spec_rack_deflater.rb", "test/spec_rack_directory.rb", "test/spec_rack_etag.rb", "test/spec_rack_fastcgi.rb", "test/spec_rack_file.rb", "test/spec_rack_handler.rb", "test/spec_rack_head.rb", "test/spec_rack_lint.rb", "test/spec_rack_lobster.rb", "test/spec_rack_lock.rb", "test/spec_rack_logger.rb", "test/spec_rack_methodoverride.rb", "test/spec_rack_mock.rb", "test/spec_rack_mongrel.rb", "test/spec_rack_nulllogger.rb", "test/spec_rack_recursive.rb", "test/spec_rack_request.rb", "test/spec_rack_response.rb", "test/spec_rack_rewindable_input.rb", "test/spec_rack_runtime.rb", "test/spec_rack_sendfile.rb", "test/spec_rack_session_cookie.rb", "test/spec_rack_session_memcache.rb", "test/spec_rack_session_pool.rb", "test/spec_rack_showexceptions.rb", "test/spec_rack_showstatus.rb", "test/spec_rack_static.rb", "test/spec_rack_thin.rb", "test/spec_rack_urlmap.rb", "test/spec_rack_utils.rb", "test/spec_rack_webrick.rb", "test/spec_rackup.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<test-spec>, [">= 0"])
      s.add_development_dependency(%q<activesupport>, ["< 2"])
      s.add_development_dependency(%q<camping>, ["< 1.6"])
      s.add_development_dependency(%q<fcgi>, [">= 0"])
      s.add_development_dependency(%q<memcache-client>, [">= 0"])
      s.add_development_dependency(%q<mongrel>, [">= 0"])
      s.add_development_dependency(%q<thin>, ["< 1.2"])
    else
      s.add_dependency(%q<test-spec>, [">= 0"])
      s.add_dependency(%q<activesupport>, ["< 2"])
      s.add_dependency(%q<camping>, ["< 1.6"])
      s.add_dependency(%q<fcgi>, [">= 0"])
      s.add_dependency(%q<memcache-client>, [">= 0"])
      s.add_dependency(%q<mongrel>, [">= 0"])
      s.add_dependency(%q<thin>, ["< 1.2"])
    end
  else
    s.add_dependency(%q<test-spec>, [">= 0"])
    s.add_dependency(%q<activesupport>, ["< 2"])
    s.add_dependency(%q<camping>, ["< 1.6"])
    s.add_dependency(%q<fcgi>, [">= 0"])
    s.add_dependency(%q<memcache-client>, [">= 0"])
    s.add_dependency(%q<mongrel>, [">= 0"])
    s.add_dependency(%q<thin>, ["< 1.2"])
  end
end
