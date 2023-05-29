class AddColumnPostTestEfikasi < ActiveRecord::Migration[7.0]
  def change
    add_column :tests, :post_test_efikasi, :integer
  end
end
