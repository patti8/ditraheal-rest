class ChangeColumnNoHpIdenty < ActiveRecord::Migration[7.0]
    def change
        change_column :identies, :no_hp, :string
    end
end