# User Profiles plugin for Redmine
# Copyright (C) 2011  Haruyuki Iida
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

require File.dirname(__FILE__) + '/../test_helper'

class UserProfileTest < ActiveSupport::TestCase
  fixtures :users, :user_profiles

  context "user.preference.prof" do
    setup do
      @user = User.generate_with_protected!(:login => 'test_user')
      @user.preference = UserPreference.generate!
    end
    
    should "nil for new user instance" do
      assert_nil @user.preference.prof
    end
    
    should "create new UserProfile inscance" do
      count = UserProfile.find(:all).length
      @user.preference.prof = "hoge"
      @user.preference.save!
      
      assert_equal(count + 1, UserProfile.find(:all).length)
    end
  end
  
  context "destroy_by_user_id" do
    setup do
      @user = User.generate_with_protected!(:login => 'test_user')
      @user.preference = UserPreference.generate!
    end
    
    should "do nothing if user.prefirence.prof is nil." do
      count = UserProfile.find(:all).length
      UserProfile.destroy_by_user_id(@user.id)
      assert_equal(count, UserProfile.find(:all).length)
    end
    
    should "remove user_profile record if user.prefirence.prof is not nil." do      
      @user.preference.prof = "bar"
      count = UserProfile.find(:all).length
      UserProfile.destroy_by_user_id(@user.id)
      assert_equal(count - 1, UserProfile.find(:all).length)
    end
  end
  
  context "User.preference.destroy" do
    setup do
      @user = User.generate_with_protected!(:login => 'test_user')
      @user.preference = UserPreference.generate!
    end
    
    should "destroy UserProfile instance." do
      assert_nil @user.preference.prof
      @user.preference.prof = "hoge"
      @user.save!
      count = UserProfile.find(:all).length
      @user = User.find(@user.id)
      #assert_equal("hoge", @user.preference.user_profile.content)
      @user.preference.destroy
      assert_equal(count - 1, UserProfile.find(:all).length)
    end
  end
end
