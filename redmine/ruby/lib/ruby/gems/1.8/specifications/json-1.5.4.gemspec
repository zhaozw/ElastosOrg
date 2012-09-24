# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{json}
  s.version = "1.5.4"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Florian Frank"]
  s.date = %q{2011-08-31 00:00:00.000000000Z}
  s.description = %q{This is a JSON implementation as a Ruby extension in C.}
  s.email = %q{flori@ping.de}
  s.executables = ["edit_json.rb", "prettify_json.rb"]
  s.extensions = ["ext/json/ext/parser/extconf.rb", "ext/json/ext/generator/extconf.rb"]
  s.files = ["./tests/test_json_string_matching.rb", "./tests/test_json_fixtures.rb", "./tests/test_json_unicode.rb", "./tests/test_json_addition.rb", "./tests/test_json_generate.rb", "./tests/test_json_encoding.rb", "./tests/test_json.rb", "bin/edit_json.rb", "bin/prettify_json.rb", "ext/json/ext/parser/extconf.rb", "ext/json/ext/generator/extconf.rb"]
  s.homepage = %q{http://flori.github.com/json}
  s.require_paths = ["ext/json/ext", "ext", "lib"]
  s.rubyforge_project = %q{json}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{JSON Implementation for Ruby}
  s.test_files = ["./tests/test_json_string_matching.rb", "./tests/test_json_fixtures.rb", "./tests/test_json_unicode.rb", "./tests/test_json_addition.rb", "./tests/test_json_generate.rb", "./tests/test_json_encoding.rb", "./tests/test_json.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<permutation>, [">= 0"])
      s.add_development_dependency(%q<bullshit>, [">= 0"])
      s.add_development_dependency(%q<sdoc>, [">= 0"])
    else
      s.add_dependency(%q<permutation>, [">= 0"])
      s.add_dependency(%q<bullshit>, [">= 0"])
      s.add_dependency(%q<sdoc>, [">= 0"])
    end
  else
    s.add_dependency(%q<permutation>, [">= 0"])
    s.add_dependency(%q<bullshit>, [">= 0"])
    s.add_dependency(%q<sdoc>, [">= 0"])
  end
end
