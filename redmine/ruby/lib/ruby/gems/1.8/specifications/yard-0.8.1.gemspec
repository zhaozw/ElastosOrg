# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{yard}
  s.version = "0.8.1"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Loren Segal"]
  s.date = %q{2012-05-02}
  s.description = %q{    YARD is a documentation generation tool for the Ruby programming language.
    It enables the user to generate consistent, usable documentation that can be
    exported to a number of formats very easily, and also supports extending for
    custom Ruby constructs such as custom class level definitions.
}
  s.email = %q{lsegal@soen.ca}
  s.executables = ["yard", "yardoc", "yri"]
  s.files = ["bin/yard", "bin/yardoc", "bin/yri"]
  s.homepage = %q{http://yardoc.org}
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{yard}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Documentation tool for consistent and usable documentation in Ruby.}

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
    else
    end
  else
  end
end
