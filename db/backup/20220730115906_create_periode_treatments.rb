class CreatePeriodeTreatments < ActiveRecord::Migration[7.0]
  def change
    create_table :periode_treatments do |t|
      t.integer :identitas_id
      t.integer :status
      t.date :tanggal_awal
      t.date :tanggal_akhir

      t.timestamps
    end
  end
end
