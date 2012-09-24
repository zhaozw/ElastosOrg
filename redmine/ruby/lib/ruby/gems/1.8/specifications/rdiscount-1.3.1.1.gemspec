# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{rdiscount}
  s.version = "1.3.1.1"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Ryan Tomayko", "Andrew White"]
  s.date = %q{2009-01-31}
  s.default_executable = %q{rdiscount}
  s.email = %q{r@tomayko.com}
  s.executables = ["rdiscount"]
  s.extensions = ["ext/extconf.rb"]
  s.files = ["test/markdown_test.rb", "test/rdiscount_test.rb", "bin/rdiscount", "ext/extconf.rb"]
  s.homepage = %q{http://github.com/rtomayko/rdiscount}
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{wink}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Fast Implementation of Gruber's Markdown in C}
  s.test_files = ["test/markdown_test.rb", "test/rdiscount_test.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 2

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
    else
    end
  else
  end
end
