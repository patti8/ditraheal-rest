
# JENIS 1 => TEST EFIKASI 
# JENIS 2 => TEST LEVEL TRAUMA

class Api::V1::PreTestController < WsController
    before_action :cek_pre_test
    before_action :cek_periode_treatment, only: %i[effication_pre_test, level_trauma_pre_test]

    # post efficacious_test 
    def effication_pre_test
        
        # jika belum ada pre test 
        if cek_pre_test.present?

            # @respon = "pre test telah dilakukan"

            @skor_efikasis = SkorEfikasi.new(test_params)
            @skor_efikasis.pre_test_id = cek_pre_test.id
            @skor_efikasis.jenis = 1
            
            

            @soal = Reference.where(jenis: 2)

            @cek_efikasi_selesai = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_pre_test.id} AND skor_efikasis.referensi_soal = #{@soal.last.id}")
   
            if @cek_efikasi_selesai.present?
                
                @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_pre_test.id}")
                    
                cek_pre_test.update(total_skor_efikasi: @hitung_efikasi.average(:jawaban).to_f.round)
                
                @respon = "Pre Test skor efikasi berhasil diupdate. #{cek_pre_test}" 

            else
                # validasi soal 
                @cek_pre_test_efikasi = SkorEfikasi.find_by(referensi_soal: @skor_efikasis.referensi_soal, pre_test_id: @skor_efikasis.pre_test_id)

                if Resources::ReferensiSoal.efikasi_by(params[:referensi_soal]).present?
                    if @skor_efikasis.save                        
                        @respon = "soal no. #{@skor_efikasis.referensi_soal} berhasil disimpan"
                        @status = 200
                    elsif @cek_pre_test_efikasi.present?
                        @respon = @cek_pre_test_efikasi.update(jawaban: @skor_efikasis.jawaban)
                        @status = 200
                    else
                      @respon = "Periksa kembali data yang dimasukan. Apakah sudah diisikan dengan benar? #{@skor_efikasis.errors.full_messages}"
                      @status = 400
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
                    @status = 200
                else
                    @respon = "Gagal menambahkan pre test, cek ulang kembali data yang dikirim. #{@pre_test.errors.full_messages}"
                    @status = 400
                end

            else
                @respon = "Periode treatment tidak ditemukan, silahkan dibuat dulu"                
            end 

       
        end       
       
        # render :json => {"code": 200, success: true, "message": "#{@respon}.", data: @skor_efikasis}  
        render :json => {success: if @status == 200 then true else false end, "message": "#{@respon}.", data: @skor_efikasis}, status: @status
        
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

            @cek_level_trauma_selesai = LevelTrauma.where("level_traumas.pre_test_id = #{cek_pre_test.id} AND level_traumas.referensi_soal = #{@soal.last.id}")
    
            # if cek_soal.present?
                if @cek_level_trauma_selesai.present?
                
                    @hitung = LevelTrauma.where("level_traumas.pre_test_id = #{cek_pre_test.id}")
                        
                    cek_pre_test.update(total_level_trauma_id: @hitung.average(:jawaban).to_f.round)
                        
                    @respon = "Pre Test level trauma berhasil diupdate"
                    @status = 200
                 
                else 
                    # cek soal 
                    if Resources::ReferensiSoal.level_trauma_by(params[:referensi_soal]).present?
                        if @level_trauma.save
                            @respon = "soal no. #{@level_trauma.referensi_soal} berhasil disimpan"
                            @status = 200
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
            render :json => {success: false, "message": "Gagal", data: skor}, status: 402
        end
    end

    private 

        def cek_pre_test
          cek_pre_test = PreTest.find_by(periode_treatment_id: params[:periode_treatment_id])
        end

        def batasi_per_user
        end


        def test_params
            params.permit(:referensi_soal, :jawaban, :pre_test_id, :jenis)
        end

        def cek_periode_treatment
            @periode_treatment_id = PeriodeTreatment.find_by(id: params[:periode_treatment_id])
        end

end