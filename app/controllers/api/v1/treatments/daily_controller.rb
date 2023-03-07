class Api::V1::Treatments::DailyController < WsController


    def show
        
      @treat = Treatment.where(periode_treatment_id: params[:periode_treatment_id]).select("
        treatments.tanggal,
        treatments.id,
        treatments.treat,
        mt.deskripsi,
        mt.ref_sesi,
        rb.ref as rule_base,
        treatments.periode_treatment_id,
        treatments.checklist
        ")
        .joins("
          LEFT JOIN master_treatments mt ON mt.id = treatments.treat
          LEFT JOIN rule_baseds rb ON rb.id = mt.rule_based_id
          ")
             
        if params[:tanggal]
          @treat = @treat.where(tanggal: params[:tanggal])
        else
          @treat = @treat.where(tanggal: Time.now.strftime("%Y-%m-%d"))
        end

        if @treat.present?
          render :json => {code: if status == 200 then 200 else 400 end, success: true, messages: "successfully for get data", data: @treat}
        else
          render :json => {code: if status == 200 then 200 else 400 end, success: false, messages: "data not found", data: nil}, success: 400
        end

    end

    def create
      # debugger
      @treat = Treatment.find_by(id: params[:id])
      
      if @treat.present?
        if @treat.checklist == false then @treat.update(checklist: true) else @treat.update(checklist: false) end 
        render json: {success: 200, messages: "checklist successfully updated", data: @treat}, success: 200
      else
        render json:  {success: 400, messages: false, data: nil}, success: 400
      end
    end

end