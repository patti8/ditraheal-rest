# JENIS => [1: pre test, 2: post test]

class Resources::TestGenerator

    def self.test(model, data, referensi_soal)
            # cek_test = data[0]
            # jenis = data[1]
            # params = data[2]
            # test_params = data[3]
            # jenis_soal = data[4]

            @test = model.new(data[3])
            @test.pre_test_id = data[0].id
            @test.jenis = data[1]

            @soal = Reference.where(jenis: data[4])

            @cek_test_selesai = @test.referensi_soal >= @soal.last.id 

            if @test.present?
                @cek_test = model.find_by(referensi_soal: @test.referensi_soal, pre_test_id: @test.pre_test_id)
            
                if referensi_soal.present?
                    if @test.save                        
                        
                        @respon = "soal no. #{@test.referensi_soal} berhasil disimpan"
                        @status = 200

                        # UPDATE SKOR 
                        # if @cek_efikasi_selesai.present?
                        #     @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_test.id}")
                        #     cek_test.update(total_skor_efikasi: @hitung_efikasi.average(:jawaban).to_f.round)
                        # end

                    elsif @cek_test.present?
                        
                        if @cek_test.update(jawaban: @test.jawaban)
                            
                            @respon = "soal no. #{@test.referensi_soal} berhasil diupdate"
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

                # OUTPUT 
                [
                    @respon,
                    @status,
                    @test
                ]

            end
    end

    def self.effication(cek_test=nil, jenis, params, test_params)
        
        if cek_test.present?
            
            data = [
                cek_test,
                jenis,
                params,
                test_params,
                2
            ]

            test(
                SkorEfikasi, 
                data, 
                Resources::ReferensiSoal.efikasi_by(params[:referensi_soal])
            )

        end

    end

    def self.level_trauma(pre_test = nil, jenis)
    
    end

end