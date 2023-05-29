class Api::V1::IdentiesController < WsController
  before_action :set_identy, only: %i[ show update destroy ]
  before_action :authorized, except: %i[add_identy]
  # before_action :current_user, only: %i[index, show, destroy, update]

  # GET /identies
  def index
      @identies = Identy.all

      render json:{code: 200, success: true, "messages": "data berhasil diambil.", data: @identies}, success: true 
  end

  # GET /identies/1
  def show
    if @identy.present?
      render json: {code: 200, success: true, "messages": "data berhasil diambil.", data: @identy}, success: true 
    else
      render json:{code: 400, success: false, "messages": "identy not found", data: @identy}, success: true 
    end
  end

  # POST /identies
  def add_identy
    @identy = Identy.new(identy_params)

    if @identy.save

      render :json => {"code": 200, success: true, "messages": "identy create success", data: @identy} 
      
    else
    
      render :json => {"code": 204, success: false, "messages": "#{@identy.errors.full_messages}", data: nil} 
    
    end
  end

  # PATCH/PUT /identies/1
  def update
    if @identy.update(identy_params)
      render :json => {"code": 200, success: true, "messages": "identy create success", data: @identy} 
    else      
      render :json => {"code": 200, success: true, "messages": "identy create success", data: @identy} 
    end
  end

  # DELETE /identies/
  def destroy
    if @identy.destroy
      render json: "Identy deleted successfully."
    else
      render json: "Data is not found or deleted."
    end
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_identy
      @identy = Identy.find(params[:id])
    end

    # Only allow a list of trusted parameters through.
    def identy_params
      params.require(:identy).permit(:no_hp, :tanggal_lahir, :alamat, :follower, :hobi, :name)
    end
end
