class CreateLogins < ActiveRecord::Migration[7.0]
  def change
    create_table :logins do |t|
      t.integer :id_user
      t.integer :status

      t.timestamps
    end
  end
end
