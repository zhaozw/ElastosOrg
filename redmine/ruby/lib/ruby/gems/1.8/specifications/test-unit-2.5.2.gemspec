# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{test-unit}
  s.version = "2.5.2"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Kouhei Sutou", "Haruka Yoshihara"]
  s.date = %q{2012-08-28}
  s.description = %q{Ruby 1.9.x bundles minitest not Test::Unit. Test::Unit
bundled in Ruby 1.8.x had not been improved but unbundled
Test::Unit (test-unit) is improved actively.
}
  s.email = ["kou@cozmixng.org", "yoshihara@clear-code.com"]
  s.files = ["test/testunit-test-util.rb", "test/test-testcase.rb", "test/ui/test_testrunmediator.rb", "test/test_testresult.rb", "test/test-pending.rb", "test/test-emacs-runner.rb", "test/test-fixture.rb", "test/test-attribute.rb", "test/test_failure.rb", "test/test-data.rb", "test/test_testsuite.rb", "test/collector/test_objectspace.rb", "test/collector/test-descendant.rb", "test/collector/test_dir.rb", "test/collector/test-load.rb", "test/run-test.rb", "test/test-assertions.rb", "test/test-priority.rb", "test/util/test_backtracefilter.rb", "test/util/test_observable.rb", "test/util/test_procwrapper.rb", "test/util/test-method-owner-finder.rb", "test/util/test-output.rb", "test/fixtures/plus.csv", "test/test-fault-location-detector.rb", "test/test-code-snippet.rb", "test/test_error.rb", "test/test-diff.rb", "test/test-notification.rb", "test/test-color-scheme.rb", "test/test-omission.rb", "test/test-color.rb"]
  s.homepage = %q{http://test-unit.rubyforge.org/}
  s.licenses = ["Ruby's and PSFL (lib/test/unit/diff.rb)"]
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{test-unit}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{test-unit - Improved version of Test::Unit bundled in Ruby 1.8.x.}
  s.test_files = ["test/testunit-test-util.rb", "test/test-testcase.rb", "test/ui/test_testrunmediator.rb", "test/test_testresult.rb", "test/test-pending.rb", "test/test-emacs-runner.rb", "test/test-fixture.rb", "test/test-attribute.rb", "test/test_failure.rb", "test/test-data.rb", "test/test_testsuite.rb", "test/collector/test_objectspace.rb", "test/collector/test-descendant.rb", "test/collector/test_dir.rb", "test/collector/test-load.rb", "test/run-test.rb", "test/test-assertions.rb", "test/test-priority.rb", "test/util/test_backtracefilter.rb", "test/util/test_observable.rb", "test/util/test_procwrapper.rb", "test/util/test-method-owner-finder.rb", "test/util/test-output.rb", "test/fixtures/plus.csv", "test/test-fault-location-detector.rb", "test/test-code-snippet.rb", "test/test_error.rb", "test/test-diff.rb", "test/test-notification.rb", "test/test-color-scheme.rb", "test/test-omission.rb", "test/test-color.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<bundler>, [">= 0"])
      s.add_development_dependency(%q<rake>, [">= 0"])
      s.add_development_dependency(%q<yard>, [">= 0"])
    else
      s.add_dependency(%q<bundler>, [">= 0"])
      s.add_dependency(%q<rake>, [">= 0"])
      s.add_dependency(%q<yard>, [">= 0"])
    end
  else
    s.add_dependency(%q<bundler>, [">= 0"])
    s.add_dependency(%q<rake>, [">= 0"])
    s.add_dependency(%q<yard>, [">= 0"])
  end
end
