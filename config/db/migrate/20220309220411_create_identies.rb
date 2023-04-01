class CreateIdenties < ActiveRecord::Migration[7.0]
    def change
      create_table :identies do |t|
        t.string :no_hp
        t.date :tanggal_lahir
        t.text :alamat
        t.integer :follower
        t.integer :hobi
  
        t.timestamps
      end
    end
end