# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{file-tail}
  s.version = "1.0.7"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Florian Frank"]
  s.date = %q{2011-09-25 00:00:00.000000000Z}
  s.description = %q{Library to tail files in Ruby}
  s.email = %q{flori@ping.de}
  s.files = ["tests/test_file-tail_group.rb", "tests/test_file-tail.rb"]
  s.homepage = %q{http://github.com/flori/file-tail}
  s.require_paths = ["lib"]
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{File::Tail for Ruby}
  s.test_files = ["tests/test_file-tail_group.rb", "tests/test_file-tail.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<gem_hadar>, ["~> 0.1.0"])
      s.add_runtime_dependency(%q<tins>, ["~> 0.3"])
    else
      s.add_dependency(%q<gem_hadar>, ["~> 0.1.0"])
      s.add_dependency(%q<tins>, ["~> 0.3"])
    end
  else
    s.add_dependency(%q<gem_hadar>, ["~> 0.1.0"])
    s.add_dependency(%q<tins>, ["~> 0.3"])
  end
end
