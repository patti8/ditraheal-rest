class CreateReferences < ActiveRecord::Migration[7.0]
  def change
    create_table :references do |t|
      t.integer :jenis
      t.string :deskripsi

      t.timestamps
    end
  end
end
