class DitrahealController < ActionController::Base
    #  before_action :get_token
    include Pagy::Backend
    layout "layouts/app-ditraheal"
    before_action :authenticate_admin!
    before_action :set_title
    
    def index
        # base_url = "http://localhost:3000/"
        # response = RestClient.post(base_url + "login", 
        #     {
        #         "username": "adm",
        #         "password": "jok742n"
        #     }
        # )
        # @data = response.body"
    end

    private

        # ambil token\ 
        def get_token
            base_url = ENV["ditraheal_base_url"] + "login"
            response = RestClient.post(base_url, 
                {
                    'username' => ENV['ditraheal_api_username'],
                    'password' => ENV['ditraheal_api_password']
                }.to_json,
                {
                    content_type: :json,
                    accept: :json
                }
            )   
            @data = JSON.parse(response.body)
            @token = @data['token']
        end 

        def set_title
            @title = "Ditraheal Dashboard"
        end

end
