class Ditraheal::References::MasterTreatmentController < Ditraheal::ReferencesController

    def index
        @treat = params[:rule_base_mode].present? ? MasterTreatment.where(rule_based_id: RuleBased.find_by(mode: params[:rule_base_mode]).id) : MasterTreatment.where(rule_based_id: 10)
    
        @master_treatment = MasterTreatment.new
    end
    
    def create

        forms_data = params[:forms].map { |form| JSON.parse(form) }
        if forms_data.present?
            forms_data.each do |data|
                # debugger
                MasterTreatment.create!(
                    deskripsi: data["master_treatment[deskripsi]"],
                    rule_based_id: data["master_treatment[rule_based_id]"],
                    time_duration_id: data["master_treatment[time_duration_id]"],
                    ref_sesi: data["master_treatment[ref_sesi]"],
                    status: true
                )
            end
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

    def update_status_all
        
        if params[:ids].present?
            
            params[:ids].each do |id|

                master_treatment = MasterTreatment.find_by(id: id)
        
                if master_treatment.present?
                    master_treatment.update(status: master_treatment.status == true ? false : true)
                    
                end
                
            end
            redirect_to ditraheal_references_master_treatment_index_path, notice: "status berhasil diupdate" #and return
        end
    end

    private

        def master_treatment_params
            params.require(:master_treatment).permit(:rule_based_id, :deskripsi, :time_duration_id, :ref_sesi)
        end

end