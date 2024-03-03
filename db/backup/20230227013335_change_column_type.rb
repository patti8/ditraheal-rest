class ChangeColumnType < ActiveRecord::Migration[7.0]
  def change
    
    change_column :treatment_kelompoks, :saling_mendoakan_sesama_anggota_kelompok_menurut, :boolean
    
  end
end
