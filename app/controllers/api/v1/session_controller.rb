class Api::V1::SessionController < WsController
    def new
    end
  
    def create
      user = Identy.new(params.permit(:no_hp, :tanggal_lahir))
      ceklogin = Identy.find_by(no_hp: user.no_hp, tanggal_lahir: user.tanggal_lahir)
      # puts user.no_hp
      if ceklogin.present?
        # log_in user
        session['name'] = ceklogin.name
        session['nohp'] = ceklogin.no_hp
        render json: {
          messages: "Halo #{session['name']}, kamu berhasil masuk!",
          status: 200
        }
      else
        render json: {
          error: "Periksa kembali no hp dan tanggal lahir anda.",
          status: 400
        }
      end
    end

    def destroy
        # if session.delete(user_id)
            session['name'] = nil
            session['password'] = nil
            render json: {
                messages: "Anda berhasil keluar!",
                status: 200
            }
        # end
    end
end
  