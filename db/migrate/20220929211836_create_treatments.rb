class CreateTreatments < ActiveRecord::Migration[7.0]
  def change
    create_table :treatments do |t|
      t.integer :treat
      t.boolean :check
      t.integer :periode_treatment_id

      t.timestamps
    end
  end
end
