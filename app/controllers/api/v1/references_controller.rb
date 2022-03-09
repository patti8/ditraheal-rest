class Api::V1::ReferencesController < ApplicationController
  before_action :set_reference, only: %i[ show update destroy ]

  # GET /references
  def index
    @references = Reference.all

    render json: @references
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
