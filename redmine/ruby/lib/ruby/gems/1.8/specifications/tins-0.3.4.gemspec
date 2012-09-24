# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{tins}
  s.version = "0.3.4"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Florian Frank"]
  s.date = %q{2011-12-08}
  s.description = %q{All the stuff that isn't good/big enough for a real library.}
  s.email = %q{flori@ping.de}
  s.files = ["tests/test_helper.rb", "tests/tins_file_binary_test.rb", "tests/tins_lines_file_test.rb", "tests/tins_memoize_test.rb", "tests/tins_secure_write_test.rb", "tests/tins_test.rb"]
  s.homepage = %q{http://flori.github.com/tins}
  s.require_paths = ["lib"]
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Useful stuff.}
  s.test_files = ["tests/test_helper.rb", "tests/tins_file_binary_test.rb", "tests/tins_lines_file_test.rb", "tests/tins_memoize_test.rb", "tests/tins_secure_write_test.rb", "tests/tins_test.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<gem_hadar>, ["~> 0.1.4"])
      s.add_development_dependency(%q<test-unit>, ["~> 2.3"])
    else
      s.add_dependency(%q<gem_hadar>, ["~> 0.1.4"])
      s.add_dependency(%q<test-unit>, ["~> 2.3"])
    end
  else
    s.add_dependency(%q<gem_hadar>, ["~> 0.1.4"])
    s.add_dependency(%q<test-unit>, ["~> 2.3"])
  end
end
