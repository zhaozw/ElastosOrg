# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{prototype-rails}
  s.version = "3.2.1"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Xavier Noria"]
  s.date = %q{2012-01-23}
  s.email = %q{fxn@hashref.com}
  s.homepage = %q{http://github.com/rails/prototype-rails}
  s.require_paths = ["lib"]
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Prototype, Scriptaculous, and RJS for Ruby on Rails}

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_runtime_dependency(%q<rails>, ["~> 3.2"])
      s.add_development_dependency(%q<mocha>, [">= 0"])
    else
      s.add_dependency(%q<rails>, ["~> 3.2"])
      s.add_dependency(%q<mocha>, [">= 0"])
    end
  else
    s.add_dependency(%q<rails>, ["~> 3.2"])
    s.add_dependency(%q<mocha>, [">= 0"])
  end
end
