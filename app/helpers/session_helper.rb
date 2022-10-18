module SessionHelper 
    def log_in(user)
        session[:user_id] = user.id
    end

    def current_user
        @current_user ||= Identy.find_by(no_hp: session[:no_hp])
    end

    def logged_in?
        !current_user.nil?
    end
end