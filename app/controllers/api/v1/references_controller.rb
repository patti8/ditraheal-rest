class Api::V1::ReferencesController < WsController
  before_action :set_reference, only: %i[ show update destroy ]
  before_action :authorized , except: %i[hobby]

  # GET /references hobby
  def hobby
    @hobi = Reference.where(jenis: 1)

    render :json => {"code": 200, success: true, "message": "hobby references success", data: @hobi}  
  end

  # GET /reference/tes_efikasi
  def effication_test
    @effication_test = Reference.where(jenis: 2)

    render :json => {"code": 200, success: true, "message": "authentication success", data: @effication_test}  
  end

   # GET /reference/level_trauma
  def level_trauma
    @effication_test = Reference.where(jenis: 3)

    render :json => {"code": 200, success: true, "message": "authentication success", data: @effication_test}  
  end


  # GET /references/1
  def show
    render json: @reference
  end

  # POST /references
  def create
    @reference = Reference.new(reference_params)

    if @reference.save
      render json: @reference, status: :created, location: @reference
    else
      render json: @reference.errors, status: :unprocessable_entity
    end
  end

  # PATCH/PUT /references/1
  def update
    if @reference.update(reference_params)
      render json: @reference
    else
      render json: @reference.errors, status: :unprocessable_entity
    end
  end

  # DELETE /references/1
  def destroy
    @reference.destroy
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_reference
      @reference = Reference.find(params[:id])
    end

    # Only allow a list of trusted parameters through.
    def reference_params
      params.require(:reference).permit(:jenis, :deskripsi)
    end
end
