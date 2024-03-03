class CreatePreTests < ActiveRecord::Migration[7.0]
  def change
    create_table :pre_tests do |t|
      t.integer :total_skor_efikasi
      t.integer :total_level_trauma_id
      t.integer :periode_treatment_id

      t.timestamps
    end
  end
end
