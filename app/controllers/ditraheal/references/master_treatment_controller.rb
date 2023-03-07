class Ditraheal::References::MasterTreatmentController < Ditraheal::ReferencesController

    def index
        @treat = params[:rule_base_mode].present? ? MasterTreatment.where(rule_based_id: RuleBased.find_by(mode: params[:rule_base_mode]).id) : MasterTreatment.where(rule_based_id: 10)
    
        @master_treatment = MasterTreatment.new
    end
    
    def create
    
        @master_treatment = MasterTreatment.new(master_treatment_params)
        @master_treatment.status = true
        if @master_treatment.save
            redirect_to ditraheal_references_master_treatment_index_path, turbo: false, notice: "master treatment berhasil ditambahkan"
        else
            render :new
        end
    end

    def update_status
        master_treatment = MasterTreatment.find_by(id: params[:master_treatment_id])
        
        if master_treatment.present?
            master_treatment.update(status: master_treatment.status == true ? false : true)
            redirect_to ditraheal_references_master_treatment_index_path(rule_base_mode: params[:rule_base_mode]), turbo: false, notice: "status berhasil diupdate"
        end
        
    end

    private

        def master_treatment_params
            params.require(:master_treatment).permit(:rule_based_id, :deskripsi, :time_duration_id, :ref_sesi)
        end

end