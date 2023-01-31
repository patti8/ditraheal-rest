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
                
                Resources::TreatmentGenerator.treat_kelompok(
                    cek_test.periode_treatment_id,
                    Identy.find_by(id: PeriodeTreatment.find_by(id: cek_test.periode_treatment_id).identitas_id).hobi
                )
                
                render :json => {
                    code: if status == 200 then 200 else 400 end,
                    success: true,
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
                success: false,
                messages: false,
                data: nil
            }, success: 400
        end

    end

    def treatment_kelompok_tampil_sekali
        
        @treat = treat_kelompok(
            1, 
            params[:periode_treatment_id]
        )

        if @treat.present?
            
            data = @treat.map { |d|  {treat_kelompok_sekali: Reference.find_by(id: d['treat_kelompok_sekali']).deskripsi, checklist: d['check_treat_kelompok_sekali']} }
            
            tanggapan(
                200,
                "data ditemukan",
                data
            )

        else
            
            tanggapan(
                400,
                "data tidak ditemukan, silahkan cek lagi id periode treatment",
                nil
            )

        end
    end

    def treatment_kelompok_tampil_berulang
        
        @treat = treat_kelompok(
            2, 
            params[:periode_treatment_id]
        )

        if @treat.present?
            
            # data = @treat.map { |d|  {treat_kelompok_sekali: Reference.find_by(id: d['treat_kelompok_sekali']).deskripsi, checklist: d['check_treat_kelompok_sekali']} }
            
            tanggapan(
                200,
                "data ditemukan",
                
                @treat.select(
                    :id,
                    :periode_treatment,
                    :bercerita_tentang_hal_hal_berhubungan_dengan_hobi,
                    :bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi,
                    :saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi,
                    :saling_memotivasi_sesama_anggota_kelompok,
                    :saling_mendoakan_sesama_anggota_kelompok_menurut,
                    :keyakinan_masing_masing,
                    :melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok,
                    :link
                ).first
            )

        else
            
            tanggapan(
                400,
                "data tidak ditemukan, silahkan cek lagi id periode treatment",
                nil
            )

        end


    end

    def treat_kelompok_ceklist

    end

    private

        def treat_kelompok(jenis, params)
            @treat = TreatmentKelompok.where(
                jenis: jenis, 
                # check_treat_kelompok_sekali: nil,
                periode_treatment:  params #params[:periode_treatment_id]
                
            )
        end

end