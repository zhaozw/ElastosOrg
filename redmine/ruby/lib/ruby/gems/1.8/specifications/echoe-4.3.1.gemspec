# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = %q{echoe}
  s.version = "4.3.1"

  s.required_rubygems_version = Gem::Requirement.new(">= 1.2") if s.respond_to? :required_rubygems_version=
  s.authors = ["Evan Weaver"]
  s.cert_chain = ["-----BEGIN CERTIFICATE-----\nMIIDLjCCAhagAwIBAgIBADANBgkqhkiG9w0BAQUFADA9MQ0wCwYDVQQDDARldmFu\nMRgwFgYKCZImiZPyLGQBGRYIY2xvdWRidXIxEjAQBgoJkiaJk/IsZAEZFgJzdDAe\nFw0wNzA5MTYxMDMzMDBaFw0wODA5MTUxMDMzMDBaMD0xDTALBgNVBAMMBGV2YW4x\nGDAWBgoJkiaJk/IsZAEZFghjbG91ZGJ1cjESMBAGCgmSJomT8ixkARkWAnN0MIIB\nIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5C0Io89nyApnr+PvbNFge9Vs\nyRWAlGBUEMahpXp28VrrfXZT0rAW7JBo4PlCE3jl4nE4dzE6gAdItSycjTosrw7A\nIr5+xoyl4Vb35adv56TIQQXvNz+BzlqnkAY5JN0CSBRTQb6mxS3hFyD/h4qgDosj\nR2RFVzHqSxCS8xq4Ny8uzOwOi+Xyu4w67fI5JvnPvMxqrlR1eaIQHmxnf76RzC46\nQO5QhufjAYGGXd960XzbQsQyTDUYJzrvT7AdOfiyZzKQykKt8dEpDn+QPjFTnGnT\nQmgJBX5WJN0lHF2l1sbv3gh4Kn1tZu+kTUqeXY6ShAoDTyvZRiFqQdwh8w2lTQID\nAQABozkwNzAJBgNVHRMEAjAAMAsGA1UdDwQEAwIEsDAdBgNVHQ4EFgQU+WqJz3xQ\nXSea1hRvvHWcIMgeeC4wDQYJKoZIhvcNAQEFBQADggEBAGLZ75jfOEW8Nsl26CTt\nJFrWxQTcQT/UljeefVE3xYr7lc9oQjbqO3FOyued3qW7TaNEtZfSHoYeUSMYbpw1\nXAwocIPuSRFDGM4B+hgQGVDx8PMGiJKom4qLXjO40UZsR7QyN/u869Vj45LURm6h\nMBcPeqCASI+WNprj9+uZa2kmHiitrFqqfMBNlm5IFbn9XeYSta9AHVvs5QQqV2m5\nhIPfLqCyxsn/YgOGvo6iwyQTWyTswamaAC3HRWZxIS1sfn/Ssqa7E7oQMkv5FAXr\nx5rKePfXINf8XTJczkl9OBEYdE9aNdJsJpXD0asLgGVwBICS5Bjohp6mizJcDC1+\nyZ0=\n-----END CERTIFICATE-----\n"]
  s.date = %q{2010-05-01}
  s.description = %q{A Rubygems packaging tool that provides Rake tasks for documentation, extension compiling, testing, and deployment.}
  s.email = %q{}
  s.homepage = %q{http://blog.evanweaver.com/files/doc/fauna/echoe/}
  s.require_paths = ["lib"]
  s.rubyforge_project = %q{fauna}
  s.rubygems_version = %q{1.6.2}
  s.summary = %q{A Rubygems packaging tool that provides Rake tasks for documentation, extension compiling, testing, and deployment.}

  if s.respond_to? :specification_version then
    s.specification_version = 3

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_runtime_dependency(%q<gemcutter>, [">= 0"])
      s.add_runtime_dependency(%q<rubyforge>, [">= 0"])
    else
      s.add_dependency(%q<gemcutter>, [">= 0"])
      s.add_dependency(%q<rubyforge>, [">= 0"])
    end
  else
    s.add_dependency(%q<gemcutter>, [">= 0"])
    s.add_dependency(%q<rubyforge>, [">= 0"])
  end
end
