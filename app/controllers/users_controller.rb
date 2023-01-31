class UsersController < WsController
    
    before_action :authorized, except: [:login, :logout]
  
    # LOGGING IN
    def login
      
      @user = Identy.find_by(no_hp: params[:no_hp], tanggal_lahir: params[:tanggal_lahir])
      
      if @user
        token = encode_token({identy_id: @user.id})

        historytoken = HistoryToken.find_by(user: @user.id)

        if historytoken.present?
          historytoken.update(token: token)
        else 
          HistoryToken.create(user: @user.id, token: token)
        end
        session['name'] = @user.id
        render :json => {"code": 200, success: true, messages: "authentication success", data: [id: @user.id, name: @user.name, no_hp: @user.no_hp, tanggal_lahir: @user.tanggal_lahir, token: token]}  
        # render json: {user: @user.name, token: token}
      else
        render :json => {"code": 204, success: false, messages: "Invalid phone number or date of birth", data: nil}  

      end
    end

    # POST LOGOUT
    def logout
      render json: {
        message: "Bye!"
      }
    end

    def cek_token
      historytoken = HistoryToken.find_by(token: params[:by_token])
      user = Identy.find_by(id: historytoken.user)
      if params[:by_token].present? && historytoken.present?
        render :json => {"code": 200, success: true, messages: "authentication success", data: [id: user.id, name: user.name, no_hp: user.no_hp, tanggal_lahir: user.tanggal_lahir, token: historytoken.token, last_login: historytoken.updated_at]}  
      end
    end

    private

end
