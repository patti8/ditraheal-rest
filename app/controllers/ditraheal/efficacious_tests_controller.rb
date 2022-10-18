class Ditraheal::EfficaciousTestsController < DitrahealController

    def index
        @efficacious_test = TesEfikasi.all
    end

    def new
    
    end

    def show
    
    end

    def edit
    
    end

    def create
        @pre_tests 
    end

    def update
    
    end

    def destroy
    
    end

    private
        
        def set_title
            @title = "Daftar Hasil Tes Efikasi Pengguna"
        end



    


end