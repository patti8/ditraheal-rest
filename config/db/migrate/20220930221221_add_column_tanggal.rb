class AddColumnTanggal < ActiveRecord::Migration[7.0]
  def change
    add_column :treatments, :tanggal, :date
  end
end
