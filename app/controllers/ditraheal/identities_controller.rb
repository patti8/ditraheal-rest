class Ditraheal::IdentitiesController < DitrahealController

    before_action :set_find_by, only: :destroy

    def index
        
        @identities = Identy.all.order(created_at: :desc)

        # hoby = Reference.find_by('jenis= ? AND deskripsi LIKE ?', 1, "%#{params[:query]}%") # if params[:query].present?

        # puts hoby.id

        @identities = @identities.where('
            name LIKE ? OR tanggal_lahir LIKE ? OR alamat LIKE ? OR follower LIKE ?', 
            "%#{params[:query]}%", "%#{params[:query]}%", "%#{params[:query]}%", "%#{params[:query]}%"
            ) if params[:query].present?
        
        @pagy, @identities = pagy @identities.reorder(sort_column => sort_direction), items: params.fetch(:count, 10)
    end

    def sort_column
        %w{ name tanggal_lahir alamat follower hobi}.include?(params[:sort]) ? params[:sort] : "name"
    end

    def sort_direction
        %w{ asc desc }.include?(params[:direction]) ? params[:direction] : "asc"
    end

    def new
    
    end

    def show
        
        @title = "Detail Data Pengguna"
        @identy = Identy.find_by(id: params[:id])
        @periode_treatment = PeriodeTreatment.where(identitas_id: params[:id]).last
        
        # presentase pengerjaan 
        # TREATMENT PRIBADI
        total_treat = Treatment.where(periode_treatment_id: @periode_treatment.id).count.to_f
        treat_dikerjakan =  Treatment.where(periode_treatment_id: @periode_treatment.id, checklist: true).count.to_f
        @presentase = treat_dikerjakan / total_treat * 100

        # TREATMENT KELOMPOK



        @treat_kelompok_sekali = TreatmentKelompok.where(jenis: 1,  check_treat_kelompok_sekali: true).count
        @treat_pribadi = Treatment.where(periode_treatment_id: @periode_treatment.id)
        @hitung_presentase = @treat_pribadi.where(checklist: true).count.to_f / @treat_pribadi.count.to_f * 100
    
    end

    def edit
    
    end

    def create
    
    end

    def update
    
    end

    def destroy
        @identy.destroy

        redirect_to root_path, success: :see_other

    end
    
    private 

        def set_title
            @title = "Data Pengguna"
        end

        def set_find_by
            @identy = Identy.find(params[:id])
        end

end