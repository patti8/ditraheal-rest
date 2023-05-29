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
        treat_kelompok = TreatmentKelompok.where(
            periode_treatment: @periode_treatment.id,
         )

        treat_kelompok_sekali = treat_kelompok.where(jenis: 1)
        treat_kelompok_berulang = treat_kelompok.where(jenis: 2)

        # SEKALI 
        total_treat_kelompok_sekali = treat_kelompok_sekali.count
        treat_kelompok_sekali_dikerjakan = treat_kelompok_sekali.where(
            check_treat_kelompok_sekali: true
        ).count
        
        # BERULANG
        total_treat_berulang = treat_kelompok_berulang.count
        treat_kelompok_berulang_dikerjakan = treat_kelompok_berulang.where("
            bercerita_tentang_hal_hal_berhubungan_dengan_hobi = ? OR 
            bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi = ? OR
            saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi = ? OR
            saling_memotivasi_sesama_anggota_kelompok = ? OR
            saling_mendoakan_sesama_anggota_kelompok_menurut = ? OR
            keyakinan_masing_masing = ? OR
            melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok = ?
        ", true, true, true, true, true, true, true).count

        total_treat_kelompok = total_treat_kelompok_sekali + total_treat_berulang
        total_treat_kelompok_dikerjakan = treat_kelompok_sekali_dikerjakan + treat_kelompok_berulang_dikerjakan

        @presentase_treat_kelompok = total_treat_kelompok_dikerjakan.to_f / total_treat_kelompok.to_f * 100
        

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