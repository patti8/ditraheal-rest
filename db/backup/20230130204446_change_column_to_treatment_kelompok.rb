class ChangeColumnToTreatmentKelompok < ActiveRecord::Migration[7.0]
  def change
    change_column :treatment_kelompoks, :link, :int #, :column_options
  end
end
