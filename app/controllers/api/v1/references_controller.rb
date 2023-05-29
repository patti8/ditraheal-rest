class Api::V1::ReferencesController < WsController
  before_action :set_reference, only: %i[ show update destroy ]
  before_action :authorized , except: %i[hobby link_medsos]

  # GET /references hobby
  def hobby
    @hobi = Reference.where(jenis: 1)

    render :json => {"code": 200, success: true, "messages": "hobby references success", data: @hobi}  
  end

  def link_medsos
   

    if params[:hobi].present?
      @link_medsos = Reference.where(jenis: 14, ref_code: params[:hobi])
    else
      @link_medsos = Reference.where(jenis: 14)
    end

    @data = []
    
    @link_medsos.each do |medsos|
      # if medsos.ref_code == 1
      
        data = {
          title: "Group Treatment Kelompok Hobi #{Reference.find_by(id: medsos.ref_code).deskripsi}",
          deskripsi: medsos.deskripsi,
          link: medsos.deskripsi,
        }
        @data << data
      # end
    end
    
    render :json => {"code": 200, success: true, "messages": "medsos references success", data: @data}  
  end


  # GET /reference/tes_efikasi
  def effication_test
    @effication_test = Reference.where(jenis: 2)

    render :json => {"code": 200, success: true, "messages": "authentication success", data: @effication_test}  
  end

   # GET /reference/level_trauma
  def level_trauma
    @effication_test = Reference.where(jenis: 3)

    render :json => {"code": 200, success: true, "messages": "authentication success", data: @effication_test}  
  end


  # GET /references/1
  def show
    render json: @reference
  end

  # POST /references
  def create
    @reference = Reference.new(reference_params)

    if @reference.save
      render json: @reference, success: :created, location: @reference
    else
      render json: @reference.errors, success: :unprocessable_entity
    end
  end

  # PATCH/PUT /references/1
  def update
    if @reference.update(reference_params)
      render json: @reference
    else
      render json: @reference.errors, success: :unprocessable_entity
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
