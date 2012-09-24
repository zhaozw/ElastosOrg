# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{needle}
  s.version = "1.3.0"

  s.required_rubygems_version = nil if s.respond_to? :required_rubygems_version=
  s.authors = ["Jamis Buck"]
  s.autorequire = %q{needle}
  s.cert_chain = nil
  s.date = %q{2005-12-24}
  s.email = %q{jamis@37signals.com}
  s.files = ["test/ALL-TESTS.rb"]
  s.homepage = %q{http://needle.rubyforge.org}
  s.require_paths = ["lib"]
  s.required_ruby_version = Gem::Requirement.new("> 0.0.0")
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Needle is a Dependency Injection/Inversion of Control container for Ruby. It supports both type-2 (setter) and type-3 (constructor) injection. It takes advantage of the dynamic nature of Ruby to provide a rich and flexible approach to injecting dependencies.}
  s.test_files = ["test/ALL-TESTS.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 1

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
    else
    end
  else
  end
end
