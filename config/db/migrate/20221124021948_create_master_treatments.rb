class CreateMasterTreatments < ActiveRecord::Migration[7.0]
  def change
    create_table :master_treatments do |t|
      t.string :deskripsi
      t.references :rule_based, null: false, foreign_key: true
      t.references :time_duration, null: false, foreign_key: true
      t.integer :ref_sesi

      t.timestamps
    end
  end
end
