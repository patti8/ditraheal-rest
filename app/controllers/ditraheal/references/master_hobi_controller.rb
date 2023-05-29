class Ditraheal::References::MasterHobiController < Ditraheal::ReferencesController

    def index
        @hobi = Reference.where(jenis: 1)
    end
    
    def new
        @hobi = Reference.new
    end



end