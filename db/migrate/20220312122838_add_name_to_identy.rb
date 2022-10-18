class AddNameToIdenty < ActiveRecord::Migration[7.0]
    def change
      add_column :identies, :name, :string
    end
  end