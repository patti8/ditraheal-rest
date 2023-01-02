class Api::V1::Treatments::TreatmentController < WsController
    
    before_action :cek_test

    def create
        
        # UPDATE SKOR 
        if cek_test.present?
            
            @hitung = LevelTrauma.where("level_traumas.pre_test_id = #{cek_test.id}")
            
            if cek_test.update(total_level_trauma_id: @hitung.sum(:jawaban).to_f.round)
                
                @generate_lvl_trauma = Resources::TreatmentGenerator.generate_level_trauma(
                    cek_test.total_level_trauma_id, 
                    cek_test.periode_treatment_id
                )

                @generate_rule_base = Resources::TreatmentGenerator.rule_base(
                    cek_test.periode_treatment_id
                )

                Resources::Tools.generate_date_for_treatment(
                    PeriodeTreatment.find_by(id: cek_test.periode_treatment_id).rule ,
                    cek_test.periode_treatment_id,
                )
                
                render :json => {
                    code: if status == 200 then 200 else 400 end,
                    status: true,
                    messages: "successfully",
                    data: {
                        generate_lvl_trauma: @generate_lvl_trauma[:data],
                        rule_base: @generate_rule_base[:data]
                    }
                }

            end
             
        else
            render :json => {
                code: if status == 200 then 200 else 400 end,
                status: false,
                messages: false,
                data: nil
            }, status: 400
        end

    end


end