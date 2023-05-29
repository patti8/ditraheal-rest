class ApplicationController < ActionController::Base
    # before_action :require_user_logged_in!
    # before_action :set_current_nohp


    def set_current_nohp
        Current.no_hp = Identy.find_by(id: session[:no_hp])
    end

    def require_user_logged_in!
        redirect_to sign_in_path, path: 'You must be signed in' if Current.no_hp.nil?
    end



end
