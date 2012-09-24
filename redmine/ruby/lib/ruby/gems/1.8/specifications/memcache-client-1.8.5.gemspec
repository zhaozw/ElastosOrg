# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{memcache-client}
  s.version = "1.8.5"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Eric Hodel", "Robert Cottrell", "Mike Perham"]
  s.date = %q{2010-07-05}
  s.default_executable = %q{memcached_top}
  s.description = %q{A Ruby library for accessing memcached.}
  s.email = %q{mperham@gmail.com}
  s.executables = ["memcached_top"]
  s.files = ["test/test_benchmark.rb", "test/test_event_machine.rb", "test/test_mem_cache.rb", "bin/memcached_top"]
  s.homepage = %q{http://github.com/mperham/memcache-client}
  s.require_paths = ["lib"]
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{A Ruby library for accessing memcached.}
  s.test_files = ["test/test_benchmark.rb", "test/test_event_machine.rb", "test/test_mem_cache.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
    else
    end
  else
  end
end
