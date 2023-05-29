class ChangeColumnPretest < ActiveRecord::Migration[7.0]
  def change
    rename_table :pre_tests, :tests
  end
end
