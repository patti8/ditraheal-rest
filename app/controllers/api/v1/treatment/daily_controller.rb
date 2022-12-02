class Api::V1::Treatment::DailyController < WsController


    def show
        # tanggal treatment belum disimpan
        @treat = Treatment.all.select("
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
        
        render :json => {status: true, message: "successfully for get data", data: @treat}
    end

    def create

    end

end