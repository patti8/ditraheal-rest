class CreateIdenties < ActiveRecord::Migration[7.0]
  def change
    create_table :identies do |t|
      t.integer :no_hp
      t.date :tgl_lahir
      t.text :alamat
      t.integer :follower
      t.integer :hobi

      t.timestamps
    end
  end
end
