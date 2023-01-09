
# JENIS 1 => PRE TEST 
# JENIS 2 => POST TEST

class Api::V1::PreTestController < WsController
    before_action :cek_test
    before_action :cek_periode_treatment, only: %i[effication_pre_test, level_trauma_pre_test]

    # post efficacious_test 
    def effication_pre_test
        
        data = Resources::TestGenerator.effication(
            cek_test,
            1,
            params,
            test_params
        )
        
        respon = data[0]
        status = data[1]
        skor_efikasis = data[2]
        
        # render :json => {"code": 200, success: true, "messages": "#{@respon}.", data: @skor_efikasis}  
        render :json => {code: if status == 200 then 200 else 400 end, success: if status == 200 then true else false end, "messages": "#{if status == 200 then respon else "Gagal menyimpan. Cek kembali data yang dimasukan. #{skor_efikasis.errors.full_messages}" end}.", data: skor_efikasis}, success: status
        
    end

    def get_effication_pre_test
     
        @pre_test = SkorEfikasi.where(pre_test_id: params[:pre_test_id])
     
        if params[:pre_test_id].present? && @pre_test.present?

            

            render :json => {
                "code": 200, 
                success: true, 
                "messages": "authentication success.", 
                data:  @pre_test
            }  

        else

            render :json => {"code": 401, success: false, "messages": "Pre test tidak ditemukan.", data: nil}  

        end
    end


    def level_trauma_pre_test
      
        data = Resources::TestGenerator.level_trauma(
            cek_test,
            1,
            params,
            test_params
        )
        
        respon = data[0]
        status = data[1]
        level_trauma = data[2]
        
        render :json => {success: if status == 200 then true else false end, "messages": "#{if status == 200 then respon else "Gagal menyimpan. Cek kembali data yang dimasukan. #{level_trauma.errors.full_messages}" end}.", data: level_trauma}, success: status
    
    end

    def skor
        skor = Test.find_by(periode_treatment_id: params[:periode_treatment_id])

        if skor.present?
            
            @generate_lvl_trauma = Resources::TreatmentGenerator.generate_level_trauma(
                skor.total_level_trauma_id, 
                skor.periode_treatment_id
            )

            render :json => {
                code: if status == 200 then 200 else 400 end, 
                success: true, 
                "messages": "Data berhasil diambil", 
                data: {
                    generate_lvl_trauma: @generate_lvl_trauma[:data][:level_trauma],
                    skor: skor
                }
            }, 
                success: 200
        else
            render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal", data: skor}, success: 401
        end
    end

    def update_skor
        
        cek_pre_test = Test.find_by(
            periode_treatment_id: params[:periode_treatment_id],
            # jenis: if params[:test] == "pre_test" then 1 elsif params[:test] == "post_test" then 2 end
        )

        if params[:test] == "pre_test" || params[:test] == "post_test"
            
            if cek_pre_test.present?

                @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_pre_test.id}")
                @hitung_level_trauma = LevelTrauma.where("level_traumas.pre_test_id = #{cek_pre_test.id}")
    
                if cek_pre_test.update(total_skor_efikasi: @hitung_efikasi.sum(:jawaban).to_f.round, jenis: if params[:test] == "pre_test" then 1 elsif params[:test] == "post_test" then 2 end) && cek_pre_test.update(total_level_trauma_id: @hitung_level_trauma.sum(:jawaban).to_f.round, jenis: if params[:test] == "pre_test" then 1 elsif params[:test] == "post_test" then 2 end)
                    @generate_lvl_trauma = Resources::TreatmentGenerator.generate_level_trauma(
                        cek_pre_test.total_level_trauma_id, 
                        cek_pre_test.periode_treatment_id
                    )

                    render :json => {
                        "code": 200, 
                        success: true, 
                        "messages": "berhasil menyimpan.", 
                        data: {
                                generate_lvl_trauma: @generate_lvl_trauma[:data][:level_trauma],
                                pre_test: cek_pre_test
                            }
                            
                    }  
                end

            else
                render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal11111111", data: nil}, success: 401
            end

        else
            render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal", data: nil}, success: 401
        end

    end

    private 

        def test_params
            params.permit(:referensi_soal, :jawaban, :pre_test_id, :jenis)
        end

        def cek_periode_treatment
            @periode_treatment_id = PeriodeTreatment.find_by(id: params[:periode_treatment_id])
        end

end