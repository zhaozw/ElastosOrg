# User Profiles plugin for Redmine
# Copyright (C) 2010-2012  Haruyuki Iida
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.


require 'redmine'
require 'user_profiles_my_accout_hooks'
require 'user_profiles_user_patch'
require 'user_profiles_users_hooks'

Rails.configuration.to_prepare do
  # Guards against including the module multiple time (like in tests)
  # and registering multiple callbacks
  require_dependency 'user_preference'
  unless UserPreference.included_modules.include? UserProfilesUserPreferencePatch
    UserPreference.send(:include, UserProfilesUserPreferencePatch)
  end  
end

Redmine::Plugin.register :redmine_user_profiles do
  name 'Redmine User Profiles plugin'
  author 'Haruyuki Iida'
  description 'This is a plugin for Redmine lets each user edit a profile.'
  version '0.1.0'
  url 'http://www.r-labs.org/projects/userprofile'
  author_url 'http://twitter.com/haru_iida'
  requires_redmine :version_or_higher => '2.0.0'

end
 