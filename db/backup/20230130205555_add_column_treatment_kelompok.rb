class AddColumnTreatmentKelompok < ActiveRecord::Migration[7.0]
  def change
    add_column :periode_treatments, :link_group, :int
  end
end
