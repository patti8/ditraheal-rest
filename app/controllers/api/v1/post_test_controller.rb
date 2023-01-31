
# JENIS 1 => PRE TEST 
# JENIS 2 => POST TEST

class Api::V1::PostTestController < WsController
    
    before_action :cek_test
    before_action :cek_periode_treatment, only: %i[effication_pre_test, level_trauma_pre_test]

    # post efficacious_test 
    def effication_test
                
        data = Resources::TestGenerator.effication(
            cek_test,
            2,
            params,
            test_params
        )
        
        respon = data[0]
        status = data[1]
        skor_efikasis = data[2]
        
        render :json => {code: if status == 200 then 200 else 400 end, success: if status == 200 then true else false end, "messages": "#{if status == 200 then respon else "Gagal menyimpan. Cek kembali data yang dimasukan. #{skor_efikasis.errors.full_messages}" end}.", data: skor_efikasis}, success: status
        
    end

    def level_trauma_test
        
        if cek_test.present?
            data = Resources::TestGenerator.level_trauma(
                cek_test,
                2,
                params,
                test_params
            )
            
            respon = data[0]
            status = data[1]
            level_trauma = data[2]

            render :json => {code: if status == 200 then 200 else 400 end, success: if status == 200 then true else false end, "messages": "#{if status == 200 then respon else "Gagal menyimpan. Cek kembali data yang dimasukan. #{level_trauma.errors.full_messages}" end}.", data: level_trauma}, success: status
        else
            render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal menyimpan. Cek kembali data yang dimasukan.", data: nil}, success: 402
        end
    end

    def skor
        skor = Test.find_by(periode_treatment_id: params[:periode_treatment_id])

        if skor.present?
            render :json => {code: if status == 200 then 200 else 400 end, success: true, "messages": "Data berhasil diambil", data: skor}, success: 200
        else
            render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal", data: skor}, success: 401
        end
    end

    def update_skor
        
        cek_test = Test.find_by(periode_treatment_id: params[:periode_treatment_id]) 
        
        if cek_test.present?

            @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_test.id}")
            @hitung_level_trauma = LevelTrauma.where("level_traumas.pre_test_id = #{cek_test.id}")
            
            if cek_pre_test.update(total_skor_efikasi: @hitung_efikasi.sum(:jawaban).to_f.round) && cek_test.update(total_level_trauma_id: @hitung_level_trauma.sum(:jawaban).to_f.round)
                render :json => {"code": 200, success: true, "messages": "berhasil menyimpan.", data: cek_test}  
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