class HomeController < ApplicationController
  def index
    q =  "Webservice Ditraheal"
    render json: q
    # respond_to do |format|
    #   format.json :json => q
    #   format.html {  }
    # end
  end
end
