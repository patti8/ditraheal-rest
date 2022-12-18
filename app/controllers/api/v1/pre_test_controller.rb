
# JENIS 1 => TEST EFIKASI 
# JENIS 2 => TEST LEVEL TRAUMA

class Api::V1::PreTestController < WsController
    before_action :cek_pre_test
    before_action :cek_periode_treatment, only: %i[effication_pre_test, level_trauma_pre_test]

    # post efficacious_test 
    def effication_pre_test
        
        data = Resources::TestGenerator.effication(
            cek_pre_test,
            1,
            params,
            test_params
        )
        
        respon = data[0]
        status = data[1]
        skor_efikasis = data[2]
        
        # render :json => {"code": 200, success: true, "message": "#{@respon}.", data: @skor_efikasis}  
        render :json => {success: if status == 200 then true else false end, "message": "#{if status == 200 then respon else "Gagal menyimpan. Cek kembali data yang dimasukan. #{skor_efikasis.errors.full_messages}" end}.", data: skor_efikasis}, status: status
        
    end

    def get_effication_pre_test
     
        @pre_test = SkorEfikasi.where(pre_test_id: params[:pre_test_id])
     
        if params[:pre_test_id].present? && @pre_test.present?

            render :json => {"code": 200, success: true, "message": "authentication success.", data: @pre_test}  

        else

            render :json => {"code": 401, success: false, "message": "Pre test tidak ditemukan.", data: nil}  

        end
    end


    def level_trauma_pre_test
      
        # jika belum ada pre test 
        if cek_pre_test.present?

            @respon = "pre test telah dilakukan"

            @level_trauma = LevelTrauma.new(test_params)
            @level_trauma.pre_test_id = cek_pre_test.id
            @level_trauma.jenis = 1

            @soal = Reference.where(jenis: 3)
            cek_soal = @soal.select  { |v| v.id == 1 }

            @cek_level_trauma_selesai = @level_trauma.referensi_soal >= @soal.last.id #LevelTrauma.where("level_traumas.pre_test_id = #{cek_pre_test.id} AND level_traumas.referensi_soal = #{@soal.last.id}")
    
            # if cek_soal.present?
                # if @cek_level_trauma_selesai.present?
                
                if @level_trauma.present? 

                    @cek_pre_test_level_trauma = LevelTrauma.find_by(referensi_soal: @level_trauma.referensi_soal, pre_test_id: @level_trauma.pre_test_id)

                    # cek soal 
                    if Resources::ReferensiSoal.level_trauma_by(params[:referensi_soal]).present?
                        if @level_trauma.save
                            @respon = "soal no. #{@level_trauma.referensi_soal} berhasil disimpan"
                            @status = 200                        
                        
                        elsif  @cek_pre_test_level_trauma.present?
                            if @cek_pre_test_level_trauma.update(jawaban: @level_trauma.jawaban) 
                                @response = "soal no. #{@level_trauma.referensi_soal} berhasil diupdate"
                                @status = 200
                            end
                        else
                            @respon = "Periksa kembali data yang dimasukan. Apakah sudah diisikan dengan benar? #{@level_trauma.errors.full_messages}"
                            @status = 400
                            @level_trauma = nil
                        end
                    else
                        @respon = "soal tidak sesuai, mohon dicek kembali"
                        @status = 400
                    end
                    
                end
        
        
        else

            if @periode_treatment_id.present?
                @pre_test = PreTest.new(periode_treatment_id: params[:periode_treatment_id])
            
                if @pre_test.save
                    @respon = "Pre Test berhasil dibuat"
                else
                    @respon = "Gagal menambahkan pre test, cek ulang kembali data yang dikirim. #{@pre_test.errors.full_messages}"
                end

            else 
                @respon = "Periode treatment tidak ditemukan, silahkan dibuat dulu"
            end

        end       
       

        render :json => {success: if @status == 200 then true else false end, "message": "#{@respon}.", data: @level_trauma}, status: @status
    end

    def skor
        skor = PreTest.find_by(periode_treatment_id: params[:periode_treatment_id])

        if skor.present?
            render :json => {success: true, "message": "Data berhasil diambil", data: skor}, status: 200
        else
            render :json => {success: false, "message": "Gagal", data: skor}, status: 401
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