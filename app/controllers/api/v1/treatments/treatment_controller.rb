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
            
            data = @treat.map { |d|  {id: d['id'], treat_kelompok_sekali: Reference.find_by(id: d['treat_kelompok_sekali']).deskripsi, checklist: kelompok_berulang_true_false_show(d['check_treat_kelompok_sekali'])} }

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

            @treat = @treat.first
            data = [
                {
                    id: @treat.id,
                    title: "bercerita tentang hal-hal berhubungan dengan hobi",
                    treatment_kelompok_berulang: "bercerita_tentang_hal_hal_berhubungan_dengan_hobi",
                    checklist:  kelompok_berulang_true_false_show(@treat.bercerita_tentang_hal_hal_berhubungan_dengan_hobi),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at

                },
                {
                    id: @treat.id,
                    title: "bercerita aktifitas sehari hari berhubungan dengan hobi",
                    treatment_kelompok_berulang: "bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi",
                    checklist:  kelompok_berulang_true_false_show(@treat.bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi )  
                },
                {
                    id: @treat.id,
                    title: "saran untuk meningkatkan kecintaan keseruan pada hobi",
                    treatment_kelompok_berulang: "saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi",
                    checklist:  kelompok_berulang_true_false_show( @treat.saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi  ),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at
                },
                {
                    id: @treat.id,
                    title: "saran untuk meningkatkan kecintaan keseruan pada hobi",
                    treatment_kelompok_berulang: "saling_memotivasi_sesama_anggota_kelompok",
                    checklist:  kelompok_berulang_true_false_show( @treat.saling_memotivasi_sesama_anggota_kelompok ),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at
                },
                {
                    id: @treat.id,
                    title: "saling mendoakan sesama anggota kelompok menurut",
                    treatment_kelompok_berulang: "saling_mendoakan_sesama_anggota_kelompok_menurut",
                    checklist: kelompok_berulang_true_false_show( @treat.saling_mendoakan_sesama_anggota_kelompok_menurut ),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at
                },
                {
                    id: @treat.id,
                    title:  "keyakinan masing-masing",
                    treatment_kelompok_berulang:  "keyakinan_masing_masing",
                    checklist: kelompok_berulang_true_false_show( @treat.keyakinan_masing_masing ),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at
                    
                },
                {
                    id: @treat.id,
                    title: "melakukan percakapan pribadi dengan topik ringan lainnya dengan sesama anggota kelompok",
                    treatment_kelompok_berulang: "melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok",
                    checklist: kelompok_berulang_true_false_show( @treat.melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok  ),
                    created_at: @treat.created_at,
                    updated_at: @treat.updated_at
                },

            ]
                        
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

    def treat_kelompok_checklist
        
        @treat_kelompok_sekali = TreatmentKelompok.select(
                                    :id, 
                                    :treat_kelompok_sekali,
                                    :check_treat_kelompok_sekali, 
                                    :created_at, 
                                    :updated_at
                                ).find_by(id: params[:id], jenis: 1)
        
        @treat_kelompok_berulang = TreatmentKelompok.select(
                                    :id, 
                                    :created_at, 
                                    :updated_at
        ).find_by(id: params[:id], jenis: 2, treat_kelompok_sekali: nil)


        if @treat_kelompok_sekali.present? && params[:jenis_treat_kelompok] == "sekali"

            if @treat_kelompok_sekali.update(
                check_treat_kelompok_sekali: if @treat_kelompok_sekali.check_treat_kelompok_sekali == true then false else true end
            )
                
                @treat_kelompok_sekali.treat_kelompok_sekali = Reference.find_by(id: @treat_kelompok_sekali.treat_kelompok_sekali).deskripsi

                tanggapan(
                    200,
                    "data ditemukan",
                    @treat_kelompok_sekali
                )

            end

        elsif @treat_kelompok_berulang.present?  && params[:jenis_treat_kelompok] != "sekali"  # && params[:treat]
            
            if params[:treat].present?

                data_treat = @treat_kelompok_berulang
                    
                if params[:treat][:bercerita_tentang_hal_hal_berhubungan_dengan_hobi].present?
                    @treat_kelompok_berulang.update(
                        bercerita_tentang_hal_hal_berhubungan_dengan_hobi: params[:treat][:bercerita_tentang_hal_hal_berhubungan_dengan_hobi]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi].present?
                    @treat_kelompok_berulang.update(
                        bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi: params[:treat][:bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi].present?
                    @treat_kelompok_berulang.update(
                        saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi: params[:treat][:saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:saling_memotivasi_sesama_anggota_kelompok].present?
                    @treat_kelompok_berulang.update(
                        saling_memotivasi_sesama_anggota_kelompok: params[:treat][:saling_memotivasi_sesama_anggota_kelompok]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:saling_mendoakan_sesama_anggota_kelompok_menurut].present?
                    @treat_kelompok_berulang.update(
                        saling_mendoakan_sesama_anggota_kelompok_menurut: params[:treat][:saling_mendoakan_sesama_anggota_kelompok_menurut]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:keyakinan_masing_masing].present?
                    @treat_kelompok_berulang.update(
                        keyakinan_masing_masing: params[:treat][:keyakinan_masing_masing]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )

                elsif params[:treat][:melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok].present?
                    @treat_kelompok_berulang.update(
                        melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok: params[:treat][:melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok]
                    )

                    tanggapan(
                        200,
                        "data ditemukan",
                        data_treat
                    )
                else
                    tanggapan(
                        400,
                        "treatment belum diisi",
                        nil
                    )
                end

            
            end

            
        else
            
            tanggapan( 
                400,
                "data tidak ditemukan, silahkan cek lagi id periode treatment",
                nil
            )

        end

    end

    private

        def treat_kelompok(jenis, params)
            # @treat = TreatmentKelompok.where(
            #     jenis: jenis, 
            #     periode_treatment:  params
            # )

            sedang_update = TreatmentKelompok.where(jenis: 2, periode_treatment: 7)
                                    .where("updated_at > created_at and updated_at == ?", Date.today.strftime("%y-%m-%d"))
            if sedang_update.present?
                data = TreatmentKelompok.where(jenis: 2, periode_treatment: 7)
                                    .where("updated_at > created_at")
                return data
            else
                data =  TreatmentKelompok.where(
                    jenis: jenis, 
                    periode_treatment:  params
                ).where("updated_at == created_at")
                return data
            end
        end

        def kelompok_berulang_true_false_show(treat)
            if treat == nil then false else treat end
        end


end