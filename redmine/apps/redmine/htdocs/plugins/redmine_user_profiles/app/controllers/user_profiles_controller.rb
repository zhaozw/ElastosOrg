class UserProfilesController < ApplicationController
  unloadable



  def preview
    @text = params[:pref][:prof]
    render :partial => 'common/preview'
  end

end
