# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{gruff}
  s.version = "0.3.6"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Geoffrey Grosenbach"]
  s.date = %q{2009-08-21}
  s.description = %q{Beautiful graphs for one or multiple datasets. Can be used on websites or in documents.}
  s.email = %q{boss@topfunky.com}
  s.files = ["test/test_accumulator_bar.rb", "test/test_area.rb", "test/test_bar.rb", "test/test_base.rb", "test/test_bullet.rb", "test/test_dot.rb", "test/test_legend.rb", "test/test_line.rb", "test/test_mini_bar.rb", "test/test_mini_pie.rb", "test/test_mini_side_bar.rb", "test/test_net.rb", "test/test_photo.rb", "test/test_pie.rb", "test/test_scene.rb", "test/test_side_bar.rb", "test/test_sidestacked_bar.rb", "test/test_spider.rb", "test/test_stacked_area.rb", "test/test_stacked_bar.rb"]
  s.homepage = %q{http://nubyonrails.com/pages/gruff}
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{gruff}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{Beautiful graphs for one or multiple datasets.}
  s.test_files = ["test/test_accumulator_bar.rb", "test/test_area.rb", "test/test_bar.rb", "test/test_base.rb", "test/test_bullet.rb", "test/test_dot.rb", "test/test_legend.rb", "test/test_line.rb", "test/test_mini_bar.rb", "test/test_mini_pie.rb", "test/test_mini_side_bar.rb", "test/test_net.rb", "test/test_photo.rb", "test/test_pie.rb", "test/test_scene.rb", "test/test_side_bar.rb", "test/test_sidestacked_bar.rb", "test/test_spider.rb", "test/test_stacked_area.rb", "test/test_stacked_bar.rb"]

  if s.respond_to? :specification_version then
    s.specification_version = 2

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_development_dependency(%q<hoe>, [">= 1.12.1"])
    else
      s.add_dependency(%q<hoe>, [">= 1.12.1"])
    end
  else
    s.add_dependency(%q<hoe>, [">= 1.12.1"])
  end
end
