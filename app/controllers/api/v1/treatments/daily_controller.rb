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
        .where(tanggal: Time.now.strftime("%Y-%m-%d"))
        .joins("
          LEFT JOIN master_treatments mt ON mt.id = treatments.treat
          LEFT JOIN rule_baseds rb ON rb.id = mt.rule_based_id
          ")
          
        if @treat.present?
          render :json => {status: true, message: "successfully for get data", data: @treat}
        else
          render :json => {status: false, message: "data not found", data: nil}, status: 400
        end

    end

    def create
      # debugger
      @treat = Treatment.find_by(id: params[:id])
      
      if @treat.present?
        if @treat.checklist == false then @treat.update(checklist: true) else @treat.update(checklist: false) end 
        render json: {status: 200, messages: "checklist successfully updated", data: @treat}, status: 200
      else
        render json:  {status: 400, messages: false, data: nil}, status: 400
      end
    end

end