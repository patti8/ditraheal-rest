class CreateLevelTraumas < ActiveRecord::Migration[7.0]
  def change
    create_table :level_traumas do |t|
      t.integer :referensi_soal
      t.integer :jawaban
      t.integer :pre_test_id
      t.integer :post_test_id
      t.integer :jenis

      t.timestamps
    end
  end
end
