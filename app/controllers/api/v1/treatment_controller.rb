class Api::V1::TreatmentController < WsController

    # before_action :periode_by_identy,  only: :show_periode
    before_action :cek_periode_treatment_selesai, only: :periode

    def periode
  
        @periode = PeriodeTreatment.new(periode_params)
        @periode.tanggal_akhir = "0000-00-00"
        
        if @cek.present?

            render :json => {"code": 400, success: false, "messages": "Anda memiliki treatment yang belum selesai.", data: nil}, success: 400  
        
        else
            if @periode.save
                Test.create!(periode_treatment_id: @periode.id)
                render :json => {
                    "code": 200, 
                    success: true, 
                    "messages": "🎉Yeay, periode treatment berhasil dibuat.", 
                    data: @periode
                }  
            else
                render :json => {"code": 400, success: false, "messages": "#{@periode.errors.full_messages}", data: nil }, success: 400 
            end
        end
        
    
    end

    def show_periode
        
        @periode = PeriodeTreatment.where(identitas_id: params[:identitas_id])
        
        if params[:identitas_id].present? && @periode.present?
            
            # debugger
            periode = @periode.last
            treatment = Treatment.where(periode_treatment_id: periode.id) 
          
      
            # treat.tanggal_awal_treatment = @treatment.first.created_at
            # treat.tanggal_akhir_treatment = @treatment.last.created_at
            # treat.tanggal_sedang_treatment = @treatment.where(checklist: true).last.created_at
            
            render :json => {
                "code": 200, 
                success: true, 
                "messages": "authentication success.", 
                data: {
                    id: periode.id,
                    identitas_id: periode.identitas_id,
                    status: periode.status,
                    tanggal_awal: periode.tanggal_awal,
                    tanggal_akhir: periode.tanggal_akhir,
                    inferensi: periode.inferensi,
                    rule: periode.rule,
                    level_trauma: periode.level_trauma,
                    treatment_kelompok: 0,
                    tanggal_awal_treatment:  treatment.first.tanggal.strftime("%Y-%m-%d"),
                    tanggal_akhir_treatment: treatment.last.tanggal.strftime("%Y-%m-%d"),
                    tanggal_sedang_treatment:  if treatment.where(checklist: true).order(updated_at: :desc).present? then treatment.where(checklist: true).order(updated_at: :desc).first.tanggal.strftime("%Y-%m-%d") else "-" end ,
                } 
    
            }  

        else
            
            render :json => {"code": 204, success: false, "messages": "Maaf, periode treatment tidak ditemukan.", data: nil}  
        
        end

    end

    def generate
        Resources::Tools.create_treatment_by(params[:periode_treatment_id])
    end

    def by_date

       @treatment = Treatment.where(periode_treatment_id: params[:periode_treatment], tanggal: Date.today)

       if @treatment.present?
            
        render :json => {"code": 200, success: true, "messages": "berhasil menarik data.", data: @treatment}, success: 200  
       
       else

        render :json => {"code": 400, success: false, "messages": "gagal menarik data.", data: nil }, success: 400  
       
       end

    end

    def update_treat

        @treat = Treatment.find_by(id: params[:id], periode_treatment_id: params[:periode_treatment], tanggal: Date.today)

        if @treat.present?
        
         if @treat.check == true
            
            @treat.update(check: 0)
            update = "false"

         else

            @treat.update(check: 1)
            update = "true"

         end

         render :json => {"code": 200, success: true, "messages": "check berhasil diupdate menjadi #{update}.", data: @treat}, success: 200  
        
        else

         render :json => {"code": 400, success: false, "messages": "harap periksa kembali data yang dimasukan.", data: nil }, success: 400  
        
        end

    end

    private

        def periode_by_identy
        end

        def cek_periode_treatment_selesai
            @cek = PeriodeTreatment.find_by(identitas_id: params[:identitas_id], tanggal_akhir: nil)
        end


        def periode_params
            params.permit(:identitas_id, :status, :tanggal_awal, :tanggal_akhir)
        end

end