class Ditraheal::ReferencesController < DitrahealController

    def index

    end

    def level_trauma
        @target = params[:target]

        respond_to do |format|
            format.json
            format.html
            # format.turbo_stream
        end

        puts @target
    end
    
    private

        def set_title
            @title = "Pengaturan Referensi"
        end


end