# WEB SERVICE CONTROLLER 
# class WsController < ActionController::API
class WsController < ActionController::Base
    
    protect_from_forgery
    before_action :authorized
    # before_action :set_csrf_cookie
    # before_action :ceklogin, except: %i[authorized, encode_token, auth_header, decoded_token, logged_in_user, create]

    # include SessionHelper

    def mydate
        Time.now.strftime("%Y-%m-%d")
    end

    def encode_token(payload)
        payload['exp'] = 24.hours.from_now.to_i
        JWT.encode(payload, "t3stk3y#{mydate}")
    end

    def auth_header
        request.headers['Authorization']
    end

    def decoded_token
        if auth_header
            token = auth_header.split(' ')[1]

            begin
                JWT.decode(token, "t3stk3y#{mydate}", true, algorithm: 'HS256')
            rescue JWT::DecodeError
                nil
            end
        end
    end

    def logged_in_user
        if decoded_token
            identy_id = decoded_token[0]['identy_id']
            @user = Identy.find_by(id: identy_id)
        end 
    end 

    def logged_in?
        !!logged_in_user
    end

    def authorized
        render :json => {"code": 203, status: :unauthorized , "message": "authentication failed !", data: nil } unless logged_in?
    end

    private 

        def cek_pre_test
            cek_pre_test = PreTest.find_by(periode_treatment_id: params[:periode_treatment_id])
        end

        def set_csrf_cookie
            cookies["CSRF-TOKEN"] = "form_authenticity_token"
        end

end
