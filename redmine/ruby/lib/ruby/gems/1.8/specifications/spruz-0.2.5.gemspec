# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{spruz}
  s.version = "0.2.5"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Florian Frank"]
  s.date = %q{2011-02-07}
  s.default_executable = %q{enum}
  s.description = %q{All the stuff that isn't good/big enough for a real library.}
  s.email = %q{flori@ping.de}
  s.executables = ["enum"]
  s.files = ["tests/test_spruz.rb", "tests/test_spruz_memoize.rb", "bin/enum"]
  s.homepage = %q{http://flori.github.com/spruz}
  s.require_paths = ["lib"]
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Useful stuff.}
  s.test_files = ["tests/test_spruz.rb", "tests/test_spruz_memoize.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
    else
    end
  else
  end
end
