class Ditraheal::GroupSosmedController < DitrahealController

    def index
        @link_medsos = Reference.where(jenis: 14)
    end

    def new
    
    end

    def show
    
    end

    def edit
    
    end

    def create
    
    end

    def update
        
        @sosmed = Reference.where(jenis: 14).find(params[:id])
        if  @sosmed.update(link_params)
            redirect_to ditraheal_group_sosmed_index_path, notice: "Link berhasil diubah.", turbo: false
        else
            render :index
        end

    end

    def destroy
    
    end

    private
    
        def link_params
            params.permit(:deskripsi)
        end

        def set_title
            @title = "Group Sosial Media"
        end


end