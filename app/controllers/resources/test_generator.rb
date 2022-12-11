# JENIS => [1: pre test, 2: post test]

class Test::Generator


    def self.effication(pre_test=nil, jenis, params, test_params)

        if pre_test.present?

            @skor_efikasis = SkorEfikasi.new(test_params)
            @skor_efikasis.pre_test_id = cek_pre_test.id
            @skor_efikasis.jenis = jenis

            @soal = Reference.where(jenis: 2)

            @cek_efikasi_selesai = @skor_efikasis.referensi_soal >= @soal.last.id 

            if @skor_efikasis.present?
                @cek_pre_test_efikasi = SkorEfikasi.find_by(referensi_soal: @skor_efikasis.referensi_soal, pre_test_id: @skor_efikasis.pre_test_id)
            
                if Resources::ReferensiSoal.efikasi_by(params[:referensi_soal]).present?
                    if @skor_efikasis.save                        
                        
                        @respon = "soal no. #{@skor_efikasis.referensi_soal} berhasil disimpan"
                        @status = 200

                        # UPDATE SKOR 
                        if @cek_efikasi_selesai.present?
                            @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_pre_test.id}")
                            cek_pre_test.update(total_skor_efikasi: @hitung_efikasi.average(:jawaban).to_f.round)
                        end

                    elsif @cek_pre_test_efikasi.present?
                        
                        if @cek_pre_test_efikasi.update(jawaban: @skor_efikasis.jawaban)
                            
                            @respon = "soal no. #{@skor_efikasis.referensi_soal} berhasil diupdate"
                            @status = 200

                        end

                    else

                      @respon = "Periksa kembali data yang dimasukan. Apakah sudah diisikan dengan benar? #{@skor_efikasis.errors.full_messages}"
                      @status = 400

                    end

                else

                    @respon = "soal tidak sesuai, mohon dicek kembali"
                    @status = 400

                end

            end

        end

    end

    def self.level_trauma(pre_test = nil, jenis)
    end

end