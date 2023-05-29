
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
        
        
        render :json => {
            code: if status == 200 then 200 else 400 end, 
            success: if status == 200 then true else false end, 
            "messages": "#{if status == 200 then response else "Gagal menyimpan. Cek kembali data yang dimasukan. #{skor_efikasis.errors.full_messages}" end}.", 
            data: skor_efikasis
        }, success: status
        
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
        
        
        cek_pre_test = Test.find_by(
            periode_treatment_id: params[:periode_treatment_id],
            # jenis: if params[:test] == "pre_test" then 1 elsif params[:test] == "post_test" then 2 end
        )

        
        if cek_test.present?
            
            @hitung_efikasi = SkorEfikasi.where("skor_efikasis.post_test_id = #{cek_test.id}")
            
            if @hitung_efikasi
        
                if cek_pre_test.update(post_test_efikasi: @hitung_efikasi.sum(:jawaban).to_f.round) 
                    render :json => {"code": 200, success: true, "messages": "berhasil menyimpannn.", data: cek_test}  
                end

            end
        else
            render :json => {code: if status == 200 then 200 else 400 end, success: false, "messages": "Gagal", data: nil}, success: 401
        end

    end


    def validasi
        
        if params[:periode_treatment_id].present?
            # check treatment kelompok 
        
            # treatment kelompok berulang sudah lebih dari 8
            treat_kelompok_berulang = TreatmentKelompok.where(
                
                jenis: 2,
                periode_treatment: params[:periode_treatment_id],
                bercerita_tentang_hal_hal_berhubungan_dengan_hobi: true,
                bercerita_aktifitas_sehari_hari_berhubungan_dengan_hobi: true,
                saran_untuk_meningkatkan_kecintaan_keseruan_pada_hobi: true,
                saling_memotivasi_sesama_anggota_kelompok: true,
                saling_mendoakan_sesama_anggota_kelompok_menurut: true,
                melakukan_percakapan_pribadi_dengan_topik_ringan_lainnya_dengan_sesama_anggota_kelompok: true,

            ).count #must < 8

            # must > 6
            treat_kelompok_sekali = TreatmentKelompok.where(periode_treatment: params[:periode_treatment_id], jenis: 1,  check_treat_kelompok_sekali: true).count

            # must > 80%
            treat_pribadi = Treatment.where(periode_treatment_id: params[:periode_treatment_id])
            hitung_presentase = treat_pribadi.where(checklist: true).count.to_f / treat_pribadi.count.to_f * 100

            messages = {}

            
            if !treat_kelompok_berulang == 10 || treat_kelompok_berulang <= 2
                messages[:treat_kelompok_message] = "treatment berulang kurang dari 8 kali"
            elsif treat_kelompok_sekali < 3
                messages[:treat_kelompok_message] = "treatment kelompok sekali belum selesai"
            
            elsif hitung_presentase < 50
                messages[:treat_kelompok_message] = "treatment personal anda belum mencapai target 50%"
            end


            if messages.present?
                tanggapan(
                    204,
                    messages[:treat_kelompok_message],
                    messages
                )
            elsif !messages.present?
                tanggapan(
                    200,
                    "treatment telah lengkap",
                    "yeay, silahkan lanjutkan untuk post test"
                )
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

        def test_params
            params.permit(:referensi_soal, :jawaban, :pre_test_id, :jenis)
        end

        def cek_periode_treatment
            @periode_treatment_id = PeriodeTreatment.find_by(id: params[:periode_treatment_id])
        end


end