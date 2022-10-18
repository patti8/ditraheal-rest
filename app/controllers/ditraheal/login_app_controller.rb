class Ditraheal::LoginAppController < DitrahealController
    def login

    end

    def logout

    end

    private
        def set_identy
            @login = Identy.where(no_tlp: params[:no_tlp])
        end
end
