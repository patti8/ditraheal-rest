class CreateSkorEfikasis < ActiveRecord::Migration[7.0]
  def change
    # drop_table :tes_efikasis
    create_table :skor_efikasis do |t|
      t.integer :referensi_soal
      t.integer :jawaban
      t.integer :pre_test_id
      t.integer :post_test_id
      t.integer :jenis

      t.timestamps
    end
  end
end
