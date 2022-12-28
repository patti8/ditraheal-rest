class AddColumnJenisPretest < ActiveRecord::Migration[7.0]
  def change
    add_column :tests, :jenis, :integer
  end
end
