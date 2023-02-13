# JENIS => [1: pre test, 2: post test]

class Resources::TestGenerator

    def self.test(model, data, referensi_soal)
            cek_test = data[0]
            jenis = data[1] # pre test atau post test
            test_params = data[2] # record model params
            jenis_soal = data[3] # soal efikasi  atau level trauma

            @test = model.new(test_params)

            if jenis == 1
                @test.post_test_id = 0
                @test.pre_test_id = cek_test.id
            elsif jenis == 2
                @test.pre_test_id = 0
                @test.post_test_id = cek_test.id
            end
            
            @test.jenis = jenis

            @soal = Reference.where(jenis: jenis_soal)

            @cek_test_selesai = @test.referensi_soal >= @soal.last.id 
            
            
            if @test.present?
                
                if jenis == 1
                    @cek_test = model.find_by(referensi_soal: @test.referensi_soal, pre_test_id: @test.pre_test_id)
                elsif jenis == 2
                    @cek_test = model.find_by(referensi_soal: @test.referensi_soal, post_test_id: @test.post_test_id)
                end

                if referensi_soal.present?
                    
                    if @test.save      

                        if jenis == 1
                            PeriodeTreatment.find_by(id: @test.pre_test_id).update(tanggal_akhir: Time.now)
                        elsif jenis == 2
                            
                            PeriodeTreatment.find_by(id: @test.post_test_id ).update(tanggal_akhir: Time.now)
                        end

                        @respon = "soal no. #{@test.referensi_soal} berhasil disimpan"
                        @status = 200

                        # UPDATE SKOR 
                        # if @cek_efikasi_selesai.present?
                        #     @hitung_efikasi = SkorEfikasi.where("skor_efikasis.pre_test_id = #{cek_test.id}")
                        #     cek_test.update(total_skor_efikasi: @hitung_efikasi.average(:jawaban).to_f.round)
                        # end

                    elsif @cek_test.present?
                        
                        if @cek_test.update!(jawaban: @test.jawaban)
                            
                            
                            if @test.jenis == 1
                                PeriodeTreatment.find_by(id: @cek_test.pre_test_id).update(tanggal_akhir: Time.now)
                            elsif jenis == 2
                                PeriodeTreatment.find_by(id: @cek_test.post_test_id).update(tanggal_akhir: Time.now)
                            end

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
                test_params,
                2 # ref. soal efikasi
            ]

            test(
                SkorEfikasi, 
                data, 
                Resources::ReferensiSoal.efikasi_by(params[:referensi_soal])
            )

        end

    end

    def self.level_trauma(cek_test=nil, jenis, params, test_params)
        
        if cek_test.present?
            
            data = [
                cek_test,
                jenis,
                test_params,
                3 # ref. soal level trauma
            ]

            test(
                LevelTrauma, 
                data, 
                Resources::ReferensiSoal.level_trauma_by(params[:referensi_soal])
            )

        end
    end

end