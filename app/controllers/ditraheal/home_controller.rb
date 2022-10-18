class Ditraheal::HomeController < DitrahealController
    
    def index
        respond_to do |format|
            format.html {  }
            format.turbo_stream
        end
    end

    def get_reference

    end

end
