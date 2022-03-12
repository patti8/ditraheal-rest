class Api::V1::IdentiesController < ApplicationController
  before_action :set_identy, only: %i[ show update destroy ]

  # GET /identies
  def index
    @identies = Identy.all

    render json: @identies
  end

  # GET /identies/1
  def show
    render json: @identy
  end

  # POST /identies
  def create
    @identy = Identy.new(identy_params)

    if @identy.save
      render json: [status: "berhasil", data: @identy], status: :ok # ,location: @identy
    else
      render json: @identy.errors, status: :unprocessable_entity
    end
  end

  # PATCH/PUT /identies/1
  def update
    if @identy.update(identy_params)
      render json: @identy
    else
      render json: @identy.errors, status: :unprocessable_entity
    end
  end

  # DELETE /identies/1
  def destroy
    @identy.destroy
  end

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_identy
      @identy = Identy.find(params[:id])
    end

    # Only allow a list of trusted parameters through.
    def identy_params
      params.require(:identy).permit(:no_hp, :tgl_lahir, :alamat, :follower, :hobi, :name)
    end
end
