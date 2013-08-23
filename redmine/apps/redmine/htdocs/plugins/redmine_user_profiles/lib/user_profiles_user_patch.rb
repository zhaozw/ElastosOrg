# User Profiles plugin for Redmine
# Copyright (C) 2010-2011  Haruyuki Iida
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

module UserProfilesUserPreferencePatch
  def self.included(base) # :nodoc:
    base.send(:include, UserPreferenceInstanceMethodsForUserProfile)

    base.class_eval do
      unloadable # Send unloadable so it will not be unloaded in development
      after_destroy :destroy_user_profile
     
    end

  end
end

module UserPreferenceInstanceMethodsForUserProfile  
  def prof
    profile = UserProfile.find_by_user_id(user.id)
    return nil unless profile
    profile.content
  end

  def prof=(content)
    profile = UserProfile.find_or_create_by_user_id(user.id)
    profile.content = content
    profile.save!
  end
  
  def destroy_user_profile
    UserProfile.destroy_by_user_id(user.id)
  end
end
