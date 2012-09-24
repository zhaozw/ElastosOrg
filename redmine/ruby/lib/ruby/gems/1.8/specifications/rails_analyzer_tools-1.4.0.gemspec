# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{rails_analyzer_tools}
  s.version = "1.4.0"

  s.required_rubygems_version = nil if s.respond_to? :required_rubygems_version=
  s.authors = ["Eric Hodel"]
  s.cert_chain = nil
  s.date = %q{2007-05-15}
  s.description = %q{Rails Analyzer Tools contains Bench, a simple web page benchmarker, Crawler, a tool for beating up on web sites, RailsStat, a tool for monitoring Rails web sites, and IOTail, a tail(1) method for Ruby IOs.}
  s.email = %q{drbrain@segment7.net}
  s.executables = ["bench", "crawl", "rails_stat"]
  s.files = ["bin/bench", "bin/crawl", "bin/rails_stat"]
  s.homepage = %q{http://seattlerb.rubyforge.org/rails_analyzer_tools}
  s.require_paths = ["lib"]
  s.required_ruby_version = Gem::Requirement.new("> 0.0.0")
  s.rubyforge_project = %q{seattlerb}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Tools for analyzing the performance of web sites.}

  if s.respond_to? :specification_version then
    s.specification_version = 1

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_runtime_dependency(%q<SyslogLogger>, [">= 1.4.0"])
      s.add_runtime_dependency(%q<hoe>, [">= 1.2.0"])
    else
      s.add_dependency(%q<SyslogLogger>, [">= 1.4.0"])
      s.add_dependency(%q<hoe>, [">= 1.2.0"])
    end
  else
    s.add_dependency(%q<SyslogLogger>, [">= 1.4.0"])
    s.add_dependency(%q<hoe>, [">= 1.2.0"])
  end
end
